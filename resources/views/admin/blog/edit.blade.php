@extends('admin.layout.master')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800">
                    <h2 class="text-2xl font-bold ">Edit Blog Post</h2>
                    <p class=" mt-1">Share your thoughts and ideas with the world</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.blogs.update', $blog->slug) }}" method="post" class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Blog Title</label>
                                    <input type="text" 
                                           name="title" 
                                           id="title" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Enter a catchy title"
                                           value="{{ $blog->title }}">
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                                    <textarea name="short_description" id="short_description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter a short description">{{ $blog->short_description }}</textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="content" 
                                      id="content" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                      rows="15"
                                      placeholder="Write your blog content here...">{{ $blog->description }}</textarea>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter meta title" value="{{ $blog->meta_title }}">
                            </div>
                            
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter meta description">{{ $blog->meta_description }}</textarea>
                            </div>
                         </div>
                         <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                                <textarea name="meta_keywords" id="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter meta keywords">{{ $blog->meta_keywords }}</textarea>
                            </div>
                         </div>
                         <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="canonical_url" class="block text-sm font-medium text-gray-700">Canonical URL</label>
                                <input type="text" name="canonical_url" id="canonical_url" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter canonical URL" value="{{ $blog->canonical_url }}">
                            </div>
                         </div>
                         <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                                <input type="file" name="featured_image" id="featured_image" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                         </div>
                         <div class="space-y-2">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                            @if($blog->featured_image)
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                                     alt="Featured Image" 
                                     class="w-full h-48 object-cover rounded-md"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                            @else
                                <p class="text-gray-500">No image uploaded</p>
                            @endif
                         </div>
                         
                         
                         
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            
                            <div class="flex space-x-4">
                                
                                <button type="submit" 
                                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<!-- Initialize CKEditor -->
<script>
    CKEDITOR.replace('content', {
        // Optional configuration
        height: 200,
        // Add any other configuration options you need
    });
</script>
@endsection
