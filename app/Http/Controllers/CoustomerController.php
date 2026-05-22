<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoustomerController extends Controller
{
    //
    public function index(){
        $page_heading = "Dashbaord";
        
        return view('frond.dashboard',compact('page_heading'));
    }
}
