<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    public function index(){
        $blogs = Blog::all();
        return view('admin.blog.index', compact('blogs'));
    }

    public function create(){
        return view('admin.blog.create');
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
       
        $request->hasFile('featured_image') ? $image = $request->file('featured_image')->store('blogs', 'public') : $image = null;
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->description = $request->content;
        $blog->short_description = $request->short_description;
        $blog->slug = Str::slug($request->title);
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->canonical_url = $request->canonical_url;
        $blog->is_published = true;
      
        $blog->featured_image = $image;
        $blog->save();
        return redirect()->route('admin.blogs')->with('success', 'Blog created successfully');
        
    }

    public function edit($slug){
        $blog = Blog::where('slug', $slug)->first();
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $slug){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $blog = Blog::where('slug', $slug)->first();
        
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $image = $request->file('featured_image')->store('blogs', 'public');
            $blog->featured_image = $image;
        }

        $blog->title = $request->title;
        $blog->description = $request->content;
        $blog->short_description = $request->short_description;
        $blog->slug = Str::slug($request->title);
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->canonical_url = $request->canonical_url;
        $blog->is_published = true;
        $blog->save();
        
        return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully');
    }

    public function delete($slug){
        $blog = Blog::where('slug', $slug)->first();
        $blog->delete();
        return redirect()->route('admin.blogs')->with('success', 'Blog deleted successfully');
    }
    
    
}
