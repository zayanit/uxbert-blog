@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ $post->title }}
                    <a class="float-right btn btn-primary" href="{{ route('editPost', $post->id) }}">Update Post</a>
                </div>

                <div class="card-body">
                    {{ $post->content }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
