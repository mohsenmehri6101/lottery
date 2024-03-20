<?php

namespace App\Http\Controllers;
class HomeController extends Controller
{
    public function index(): bool
    {
         return view('home.index');
    }

}
