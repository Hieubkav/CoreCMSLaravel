<?php

namespace App\Http\Controllers;



class MainController extends Controller
{
    /**
     * Homepage
     */
    public function index()
    {
        return view('shop.storeFront');
    }

    /**
     * Store front (alias for index)
     */
    public function storeFront()
    {
        return view('shop.storeFront');
    }

}
