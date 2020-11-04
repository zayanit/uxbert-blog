@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Update Post</div>

                <div class="card-body">
                    <form action="{{ route('updatePost', $post->id) }}" method="post">
                        @csrf
                        @method('patch')
                        @include('posts._fields', ['butTxt' => 'Update'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
