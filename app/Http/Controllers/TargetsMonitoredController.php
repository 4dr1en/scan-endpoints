<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TargetsMonitoredController extends Controller
{
    /**
     * Display a listing of the targets by the user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('targetsMonitored.index');
    }
}