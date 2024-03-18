<?php

namespace App\Http\Controllers;
class HomeController extends Controller
{
    public function index(): bool
    {
         return send_sms('09366246101','hi i,m mohsen','farazsms');
         return view('home.index');
    }
}
