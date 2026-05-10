<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    function index(){
        $modules = Module::orderBy('sort_order', 'asc')->select('id','title','content','primary_icon', 'secondary_icon', 'secondary_content' )->get();
        return response()->json($modules);
    }
}
