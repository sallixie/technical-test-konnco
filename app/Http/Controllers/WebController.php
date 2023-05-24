<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function shop()
    {
        $items = Item::all();
        return view("shop", compact("items"));
    }
}
