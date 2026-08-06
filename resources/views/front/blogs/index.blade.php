@extends('front.layout.master')

@section('content')
@include('front.components.fade-logo',['title' => $settings['website_name']])

<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-800 mb-4 tracking-tight">Our Blog</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover the latest updates, tips, and insights from our expert team</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach ($blogs as $blog)
            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                @if($blog->featured_image)
                <div class="relative h-64 overflow-hidden">
                    <img width="800" src="{{ asset('storage/app/public/' . $blog->featured_image) }}" 
                         alt="{{ $blog->title }}" 
                         class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                @endif
                
                <div class="p-6">
                    @if($blog->category)
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-blue-600 bg-blue-50 rounded-full mb-4">
                        {{ $blog->category }}
                    </span>
                    @endif
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors duration-300">
                        <a href="{{ route('front.single.blog', $blog->slug) }}" class="hover:underline">
                            {{ $blog->title }}
                        </a>
                    </h2>
                    
                    <div class="text-gray-600 mb-4 line-clamp-3 text-base leading-relaxed">
                        {!! Str::limit(strip_tags($blog->description), 150) !!}
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <svg width="20" height="20" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-500">{{ $blog->created_at->format('M d, Y') }}</span>
                        </div>
                        <a href="{{ route('front.single.blog', $blog->slug) }}" 
                           class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700 transition-colors duration-300">
                            Read More
                            <svg width="20" height="20" class="w-4 h-4 ml-2 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        @if($blogs->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg">
            <div class="w-20 h-20 mx-auto mb-6 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No blogs found</h3>
            <p class="text-gray-600">Check back later for new content.</p>
        </div>
        @endif
    </div>
</section>

@endsection
