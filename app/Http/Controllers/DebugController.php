<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function index(){
        $province = Province::find(11);
    }
}
