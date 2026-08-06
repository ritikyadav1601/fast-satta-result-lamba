<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class BlogController extends Controller
{
    public function index(){
         Artisan::call('storage:link');
        $blogs = Blog::all();
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.blogs.index', compact('blogs', 'settings'));
    }

   
    public function show($slug){
        $blog = Blog::where('slug', $slug)->first();
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.blogs.show', compact('blog','settings'));
    }
}
