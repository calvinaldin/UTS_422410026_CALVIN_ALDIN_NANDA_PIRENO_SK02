@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<x-page-header title="Edit kategori" :subtitle="$kategori->nama" />
<form action="{{ route('kategori.update', $kategori) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    @include('kategori._form')
</form>
@endsection
