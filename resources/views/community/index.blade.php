@extends('layouts.app')

@section('content')
<div class="container">
    <h1>게시판 목록</h1>
    <a href="{{ route('community.create') }}" class="btn btn-primary mb-3">새 게시글 작성</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td><a href="{{ route('community.show', ['community' => $post->id]) }}">{{ $post->title }}</a></td>
                    <td>{{ $post->author->name }}</td>
                    <td>{{ $post->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $posts->links() }}
</div>
@endsection
