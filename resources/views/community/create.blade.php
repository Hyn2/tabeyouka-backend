@extends('layouts.app')

@section('content')
<div class="container">
    <h1>새 게시글 작성</h1>
    <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="text">내용</label>
            <textarea name="text" id="text" rows="5" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="image_file">이미지 업로드</label>
            <input type="file" name="image_file" id="image_file" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary">저장</button>
    </form>
</div>
@endsection
