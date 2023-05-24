<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        return view("checkout-payment");
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

            $order_id = Str::uuid()->toString();
            DB::beginTransaction();

            DB::table("transactions")->insert([
                "id" => $order_id,
                "user_id" => Auth::user()->id,
                "cart_id" => $cart->id,
                "total_biaya" => $cart->total_biaya,
                "bank" => $request->bank,
                "status" => "pending",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            $serverKey = config("midtrans.key");
            $response = Http::withBasicAuth($serverKey, '')
                ->post("https://api.sandbox.midtrans.com/v2/charge", [
                    "payment_type" => "bank_transfer",
                    "transaction_details" => [
                        "order_id" => $order_id,
                        "gross_amount" => $cart->total_biaya
                    ],
                    "bank_transfer" => [
                        "bank" => "bni"
                    ],
                    "customer_details" => [
                        "first_name" => Auth::user()->name,
                        "email" => Auth::user()->email,
                        "phone" => Auth::user()->telepon,
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception("Midtrans error");
            }

            DB::commit();

            return view("checkout-payment", compact("response"));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal melakukan pembayaran");
        }
    }
}
