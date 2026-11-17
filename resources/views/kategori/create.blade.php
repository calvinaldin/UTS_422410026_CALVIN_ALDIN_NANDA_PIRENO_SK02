@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')
<x-page-header title="Tambah kategori" />
<form action="{{ route('kategori.store') }}" method="POST" novalidate>
    @csrf
    @include('kategori._form')
</form>
@endsection
