<div class="form-group">
    <label for="title">Post Title</label>
    <input type="text" class="form-control" name="title" id="title" placeholder="post title" value="{{ $post->title ?? '' }}">
</div>

<div class="form-group">
    <label for="content">Post Content</label>
    <textarea class="form-control" id="content" name="content" rows="8">{{ $post->content ?? '' }}</textarea>
</div>

<button type="submit" class="btn btn-primary float-right">{{ $butTxt }}</button>