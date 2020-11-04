@foreach($posts->chunk(2) as $chunkedPosts)
<div class="row col-md-12 mt-3">
    @foreach($chunkedPosts as $post)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ $post->title }}</div>

            <div class="card-body">
                {{ \Illuminate\Support\Str::limit($post->content, 150, $end='...') }}
                <a href="{{ route('showPost', $post->id) }}" class="float-right" style="position: absolute; bottom: 5px; right: 5px;">Read more</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endforeach

<div class="text-center mt-3">
    {{ $posts->links("pagination::bootstrap-4") }}
</div>