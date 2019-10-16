<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
  
class EnrollmentController extends Controller
{ 
    public function index()
    {  
        return view('profile.enrollment');
    }  
}