<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebController extends Controller
{
    public function dashboard()
    {
        return view("dashboard");
    }

    public function login()
    {
        return view("login");
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ], [
            "email.required" => "Email harus diisi",
            "email.email" => "Email tidak valid",
            "password.required" => "Password harus diisi",
        ]);

        $credentials = $request->only("email", "password");
        if (auth()->attempt($credentials)) {
            return redirect()->route("shop");
        }
        return redirect()->back()->with("error", "Email atau password salah");
    }


    public function shop()
    {
        $items = Item::all();
        return view("shop", compact("items"));
    }

    public function cart()
    {
        $cart = Cart::with("user", "cartItems")
            ->where("user_id", auth()->user()->id)
            ->where("status", "pending")
            ->first();

        return view("cart", compact("cart"));
    }

    public function cartDelete(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->back()->with("success", "Berhasil menghapus item dari keranjang");
    }

    public function checkout()
    {
        $cart = Cart::with("user", "cartItems")
            ->where("user_id", auth()->user()->id)
            ->where("status", "pending")
            ->first();
        return view("checkout", compact("cart"));
    }

    public function checkoutPayment(Request $request)
    {
        try {
            $cart = Cart::with("user", "cartItems")
                ->find($request->cart_id)
                ->first();
            foreach ($cart->cartItems as $cartItem) {
                $item = Item::find($cartItem->item_id);
                if ($item->stok < $cartItem->jumlah_item) {
                    return redirect()->back()->with("error", "Stok " . $item->nama . " tidak cukup");
                }
            }

            $order_id = Str::uuid()->toString();
            DB::beginTransaction();

            $serverKey = config("midtrans.key");

            if ($request->bank === "echannel") {
                $midtrans = Http::withBasicAuth($serverKey, '')
                    ->post("https://api.sandbox.midtrans.com/v2/charge", [
                        "payment_type" => "echannel",
                        "transaction_details" => [
                            "order_id" => $order_id,
                            "gross_amount" => $cart->total_biaya
                        ],
                        "echannel" => [
                            "bill_info1" => "Payment For:",
                            "bill_info2" => "Order ID: " . $order_id,
                        ],
                        "customer_details" => [
                            "first_name" => Auth::user()->name,
                            "email" => Auth::user()->email,
                            "phone" => Auth::user()->telepon,
                        ],
                    ]);
                $bill_key = $midtrans["bill_key"];
                $biller_code = $midtrans["biller_code"];
            } else if ($request->bank === "permata") {
                $midtrans = Http::withBasicAuth($serverKey, '')
                    ->post("https://api.sandbox.midtrans.com/v2/charge", [
                        "payment_type" => "permata",
                        "transaction_details" => [
                            "order_id" => $order_id,
                            "gross_amount" => $cart->total_biaya
                        ],
                    ]);
                $va_number = $midtrans["permata_va_number"];
                $expiry_time = $midtrans["permata_expiration"];
            } else {
                $midtrans = Http::withBasicAuth($serverKey, '')
                    ->post("https://api.sandbox.midtrans.com/v2/charge", [
                        "payment_type" => "bank_transfer",
                        "transaction_details" => [
                            "order_id" => $order_id,
                            "gross_amount" => $cart->total_biaya
                        ],
                        "bank_transfer" => [
                            "bank" => $request->bank,
                        ],
                        "customer_details" => [
                            "first_name" => Auth::user()->name,
                            "email" => Auth::user()->email,
                            "phone" => Auth::user()->telepon,
                        ],
                    ]);
                $va_number = $midtrans["va_numbers"][0]["va_number"];
                $expiry_time = $midtrans["expiry_time"];
            }

            if ($midtrans->failed()) {
                throw new \Exception("Midtrans error");
            }

            DB::table("transactions")->insert([
                "id" => $order_id,
                "user_id" => Auth::user()->id,
                "cart_id" => $cart->id,
                "total_biaya" => $cart->total_biaya,
                "bank" => $request->bank,
                "status" => "pending",
                "va_number" => $va_number ?? null,
                "bill_key" => $bill_key ?? null,
                "biller_code" => $biller_code ?? null,
                "expiry_time" => $expiry_time ?? null,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            foreach ($cart->cartItems as $cartItem) {
                $item = DB::table("items")->where("id", $cartItem->item_id);
                $item->update([
                    "stok" => DB::raw("stok - $cartItem->jumlah_item")
                ]);
            }

            DB::commit();

            $bank = $request->bank;
            $bill_key = $bill_key ?? null;
            $biller_code = $biller_code ?? null;
            $va_number = $va_number ?? null;
            $expiry_time = $expiry_time ?? null;
            $transaction_id = $order_id;
            return view("checkout-payment", compact("va_number", "expiry_time", "cart", "bill_key", "biller_code", "bank", "transaction_id"));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal melakukan pembayaran");
        }
    }
}
