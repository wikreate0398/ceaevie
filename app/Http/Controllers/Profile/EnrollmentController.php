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
    	Tips::confirmed()->where('open', '1')->update(['open' => 0]); 
        return view('profile.enrollment', compact('tips'));
    }  
}