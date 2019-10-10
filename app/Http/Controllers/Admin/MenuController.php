<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

 
class MenuController extends Controller
{

    private $method; 

    private $returnDataFields = ['name', 'text', 'seo_title', 'seo_description', 'seo_keywords'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->method = config('admin.path') . '/menu';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    { 
        $data = [
            'menu'   => Menu::orderByRaw('page_up asc, id desc')->get(),
            'table'  => (new Menu)->getTable(),
            'method' => $this->method
        ]; 

        return view('admin.menu.list', $data);
    } 
 
    public function create(Request $request)
    { 
        $input = \Language::returnData($this->returnDataFields); 
        $input['url'] = str_slug($input['url'], '-'); 
        Menu::create($input);
        return \App\Utils\JsonResponse::success(['redirect' => route('admin_menu')], trans('admin.save')); 
    }

    public function showeditForm($id)
    {  
        return view('admin.menu.edit', ['method' => $this->method, 'data' => Menu::findOrFail($id)]);
    }

    public function update($id, Request $request)
    { 
        $data         = Menu::findOrFail($id); 
        $input = \Language::returnData($this->returnDataFields); 
        if ($request->has('url') )
        {
            $input['url'] = str_slug($input['url'], '-'); 
        }         
        $data->fill($input)->save(); 
        return \App\Utils\JsonResponse::success(['redirect' => route('admin_menu')], trans('admin.save')); 
    } 
}
