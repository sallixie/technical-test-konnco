<?php

use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/tes", function () {
    $data = [
        "users" => User::all(),
        "items" => Item::all(),
        "carts" => Cart::with("user", "cartItems")->get(),
        "transactions" => Transaction::all(),
    ];
    return $data;
});
