<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //Vizualizacion de la home
    public function __invoke(Request $request)
    {
        return view('home');
    }
}
