<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        
        return view('statistics', ['statistics' => $statistics]);
    }

}
