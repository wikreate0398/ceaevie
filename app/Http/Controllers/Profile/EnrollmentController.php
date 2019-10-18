<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Tips; 
  
class EnrollmentController extends Controller
{ 
    public function index()
    {  
    	$tips = Tips::confirmed()->orderBy('id', 'desc')->paginate(10); 
        return view('profile.enrollment', compact('tips'));
    }  
}