<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Constants\Constants;
use App\Models\Constants\ConstantsValue;
use App\Models\Constants\ConstantsCategory;

class ConstantsController extends Controller
{

    private $method = 'constants';

    private $folder = 'constants';

    private $redirectRoute = 'admin_constants';

    private $returnDataFields = ['name', 'description'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->method = config('admin.path') . '/' . $this->method;

        if (request()->test) {
            $user = \App\Models\User::whereId(1)->first();
            $user->notify(new \App\Notifications\NewItemInCart(array()));
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    { 
        $data = [
            'data'   => Constants::with('constants_value')->filter()->get()->groupBy('category.name')->sortBy('category.page_up'),
            'method' => $this->method,
            'categories' => ConstantsCategory::orderBy('page_up', 'asc')->get()
        ];

        //exit(print_arr($data['data']));

        return view('admin.'.$this->folder.'.list', $data);
    } 


    public function create(Request $request)
    {
        ConstantsValue::truncate();

        if(!empty($request->data))
        {
            $insertData = [];
            foreach ($request->data as $idConstant => $values)
            {
                foreach ($values as $lang => $value)
                {
                    $insertData[] = [
                        'lang'     => $lang,
                        'id_const' => $idConstant,
                        'value'    => htmlspecialchars(trim($value))
                    ];
                }
            }

            if(!empty($insertData)) ConstantsValue::insert($insertData);
        }

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function createConstant(Request $request)
    {

        $editor = !empty($request->editor) ? true : false;

        $dataInsert = array(
            'name'        => $request->name,
            'description' => $request->description,
            'editor'      => $editor,
            'id_category' => $request->id_category
        );

        $id = Constants::create($dataInsert)->id;

        foreach ($request->value as $key => $value)
        {
            ConstantsValue::create(['lang' => $key, 'value' => $value, 'id_const' => $id]);
        }
       // exit(print_arr($request->all()));
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save'));
    }
}
