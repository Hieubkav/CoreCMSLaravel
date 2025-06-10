<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Trang chủ website
     */
    public function index()
    {
        return view('shop.storeFront');
    }
}
