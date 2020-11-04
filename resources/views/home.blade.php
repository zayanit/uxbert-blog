@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Your Posts
                    <a class="float-right btn btn-primary" href="{{ route('createPost') }}">
                        Create Post
                    </a>
                </div>
            </div>
        </div>

        @include('posts._posts-list')

    </div>
</div>
@endsection
