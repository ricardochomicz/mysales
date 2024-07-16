<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $plans = Plan::with('modules')->get();
        return view('site', compact('plans'));
    }

    public function plan(Request $request, $slug)
    {
        if(!$plan = Plan::where('slug', $slug)->first()){
            return back();
        }

        $request->session()->put('plan', $plan);

        return redirect()->route('register');
    }
}
