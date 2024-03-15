<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
//         return send_sms('09366246101','hello mohsen faraz test','farazsms');
         return view('home.index');
    }
}
