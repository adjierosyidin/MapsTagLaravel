<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use App\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /* public function __construct()
    {
        $this->middleware('auth');
    } */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tg = Tag::searchResults();
        $tags = $tg->get();
       
        


        return view('home', compact('tags'));
    }
    public function show(Tag $tag)
    {
        return view('tag', compact('tag'));
    }
}
