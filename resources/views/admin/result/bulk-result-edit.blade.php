@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <form action="{{ route('bulk.result.update', $oldResult->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="flex flex-col">
            <label for="result">Result</label>
            <input type="text" name="result" value="{{ $oldResult->result }}" class=" w-1/2 border-2 border-gray-300 rounded-md p-2">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
    </form>
</div>
@endsection

