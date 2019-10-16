<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
  
class BallanceController extends Controller
{ 
    public function index()
    { 
        return view('profile.ballance');
    }  
}