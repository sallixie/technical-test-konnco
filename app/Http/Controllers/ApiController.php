<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}
