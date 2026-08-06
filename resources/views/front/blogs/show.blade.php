@extends('front.layout.master')

@section('meta_title', $blog->meta_title)
@section('meta_description', $blog->meta_description)
@section('meta_keywords', $blog->meta_keywords)
@section('canonical', $blog->canonical_url)
@section('content')
@include('front.components.fade-logo',['title' => $settings['website_name']])

<section class="octoberresultchart">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Featured Image -->
            @if($blog->featured_image)
            <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
                <img src="{{ asset('storage/app/public/' . $blog->featured_image) }}" 
                     alt="{{ $blog->title }}" 
                     class="w-full h-96 object-cover">
            </div>
            @endif

            <!-- Blog Content -->
            <article class="bg-white rounded-lg shadow-md p-8">
                <!-- Category and Date -->
                <div class="flex items-center justify-between mb-6">
                    @if($blog->category)
                    <span class="px-4 py-1 text-sm font-semibold text-white bg-blue-600 rounded-full">
                        {{ $blog->category }}
                    </span>
                    @endif
                    <div class="flex items-center space-x-2 text-gray-500">
                        <svg height=20 width=20 class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $blog->created_at->format('F d, Y') }}</span>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $blog->title }}</h1>
                <div class=" text-gray-700">
                    {!! $blog->description !!}
                </div>
                <!-- Tags -->
                @if($blog->tags)
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach(explode(',', $blog->tags) as $tag)
                    <span class="px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">
                        #{{ trim($tag) }}
                    </span>
                    @endforeach
                </div>
                @endif

                <!-- Content -->
                

                
            </article>

            <!-- Related Posts -->
            @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Related Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($relatedBlogs as $relatedBlog)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($relatedBlog->featured_image)
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $relatedBlog->featured_image) }}" 
                                 alt="{{ $relatedBlog->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                <a href="{{ route('front.single.blog', $relatedBlog->slug) }}" class="hover:text-blue-600">
                                    {{ $relatedBlog->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 line-clamp-2">
                                {!! Str::limit(strip_tags($relatedBlog->description), 100) !!}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
