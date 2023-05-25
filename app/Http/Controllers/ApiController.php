<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Str;

class ApiController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();

            $cartAvailable = DB::table('carts')
                ->where('user_id', $request->user_id)
                ->where('status', "pending")
                ->first();

            if ($cartAvailable) {
                $cartId = $cartAvailable->id;
            } else {
                $cartId = Str::uuid()->toString();
                DB::table('carts')->insert([
                    'id' => $cartId,
                    'user_id' => $request->user_id,
                    'status' => "pending",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $itemInCart = DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->where('item_id', $request->item_id)
                ->first();

            if ($itemInCart) {
                DB::table('cart_items')
                    ->where('cart_id', $cartId)
                    ->where('item_id', $request->item_id)
                    ->update([
                        'jumlah_item' => $request->jumlah_item,
                        'updated_at' => Carbon::now(),
                    ]);
            } else {
                DB::table('cart_items')->insert([
                    'id' => Str::uuid()->toString(),
                    'cart_id' => $cartId,
                    'item_id' => $request->item_id,
                    'jumlah_item' => $request->jumlah_item,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menambahkan item ke cart',
        ]);
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => 'required',
            "keranjang_id" => 'required',
            "bank" => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "invalid entry", "data" => $validator->errors()]);
        }

        $keranjang = Cart::with("user", "barang_keranjang")
            ->find($request->keranjang_id)
            ->first();

        if (!$keranjang) {
            return response()->json(["message" => "keranjang not found"], 404);
        }

        try {
            $order_id = Str::uuid()->toString();
            DB::beginTransaction();

            DB::table("transaksis")->insert([
                "id" => $order_id,
                "user_id" => $request->user_id,
                "keranjang_id" => $request->keranjang_id,
                "total_biaya" => $keranjang->total_biaya,
                "status" => "pending",
                "created_at" => Carbon::now(),
            ]);

            $serverKey = config("midtrans.key");
            $response = Http::withBasicAuth($serverKey, '')
                ->post("https://api.sandbox.midtrans.com/v2/charge", [
                    "payment_type" => "bank_transfer",
                    "transaction_details" => [
                        "order_id" => $order_id,
                        "gross_amount" => $keranjang->total_biaya + 100000000
                    ],
                    "bank_transfer" => [
                        "bank" => "bni"
                    ],
                    "customer_details" => [
                        "first_name" => $keranjang->user->name,
                        "email" => $keranjang->user->email,
                    ],
                    "description" => "Pembayaran untuk " . $keranjang->user->name,
                ]);

            if ($response->failed()) {
                throw new \Exception($response->json()["message"]);
            }

            DB::commit();
            return response()->json(["message" => "success", "data" => $response->json()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        Log::info("webhook", $request->all());
        $signature = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . config("midtrans.key"));

        if ($signature !== $request->signature_key) {
            return response()->json(["status" => "error", "message" => "invalid signature"], 400);
        }

        try {
            DB::beginTransaction();

            DB::table("transactions")
                ->where("id", $request->order_id)
                ->update([
                    "status" => $request->transaction_status
                ]);

            DB::table("carts")
                ->where("status", "pending")
                ->update([
                    "status" => $request->transaction_status
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["status" => "error", "message" => $e->getMessage()], 500);
        }

        return response()->json(["status" => "success", "message" => "success update transaction status"]);
    }

    public function transactionStatus(Transaction $transaction)
    {
        return $transaction->status === "pending" ? "Pending" : response()->json($transaction->status === "settlement" ? "Success" : "Failed");
    }
}
