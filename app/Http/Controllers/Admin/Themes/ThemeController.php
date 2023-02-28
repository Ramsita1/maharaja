<?php

namespace App\Http\Controllers\Admin\Themes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Posts;
use App\Option;

class ThemeController extends Controller
{
    public function index(){
        
        $view = 'Admin/Themes/Index';
        $option = ThemeSidebarOptions();
        return view('Admin',compact('view','option'));
    }
    public function store(Request $request){
        foreach ($request->all() as $key => $value) {
            updateOption($key,$value);
        }

       return Response()->json(['status'=>true,'result'=>'saved'],200);
    }
}