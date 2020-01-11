<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class researchController extends Controller
{
    //
    public function research(){
        return view('api.research');
    }
}
