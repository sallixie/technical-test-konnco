<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{
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
}
