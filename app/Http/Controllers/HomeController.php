<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $tools = config('tools');
        $featured = collect($tools)->where('feat', true)->take(6)->values();

        return view('home', [
            'tools' => $tools,
            'featured' => $featured,
        ]);
    }
}
