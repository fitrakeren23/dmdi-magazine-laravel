@extends('admin.layouts.admin')

@section('title', 'Edit Artikel - DMDI Admin')  
@section('page-title', 'Edit Artikel')

@section('content')
<form id="article-form" action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @include('admin.articles.form')
</form>
@endsection