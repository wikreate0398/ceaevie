<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\BackgroundColor; 

class WorkspaceController extends Controller
{ 
    public function index()
    { 
    	$backgrounds = BackgroundColor::orderByPageUp()->visible()->get();
        return view('profile.workspace', compact(['backgrounds'])); 
    }  
}