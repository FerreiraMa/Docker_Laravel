@extends('admin.layouts.app')

@section('title', "Editar o Post {$post->title}")

@section('content')
    <h1 class="text-center text-3x1 uppercase font-black my-4">Editar o Post <strong>{{$post->title}} </strong></h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
    <div class="w-11/12 p-12 bg-white sm:w-8/12 md:w-1/2 lg:w-5/12 mx-auto">
        <form action="{{route('posts.update', $post->id)}}" method="post" enctype="multipart/form-data">
            @method('put')
            @include('admin.posts._partials.form')

    </form>
    </div>
@endsection
