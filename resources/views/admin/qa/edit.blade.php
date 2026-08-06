@extends('admin.layout.master')

@section('content')
    <section class="mb-4">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-bold">Edit Question and Answer</h1>
        </div>
    </section>

    <section>
        <div class="container mx-auto">
            <div class="max-w-lg mx-auto">
                <form action="{{ route('question.update', $question->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="question" class="block text-gray-700 text-sm font-bold mb-2">Question</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="question" name="question" value="{{ old('question', $question->question) }}" required>
                        @error('question')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="answer" class="block text-gray-700 text-sm font-bold mb-2">Answer</label>
                        <textarea rows="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="answer" name="answer" required>{{ old('answer', $question->answer) }}</textarea>
                        @error('answer')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
