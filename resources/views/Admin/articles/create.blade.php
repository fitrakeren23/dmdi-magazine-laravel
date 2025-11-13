@extends('admin.layouts.admin')

@section('title', 'Buat Artikel - DMDI Admin')
@section('page-title', 'Buat Artikel Baru')

@section('content')
<form id="article-form" action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
    @include('admin.articles.form')
</form>
@endsection