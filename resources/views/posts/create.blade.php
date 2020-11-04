@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add New Post</div>

                <div class="card-body">
                    <form action="{{ route('storePost') }}" method="post">
                        @csrf
                        @include('posts._fields', ['butTxt' => 'Save'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
