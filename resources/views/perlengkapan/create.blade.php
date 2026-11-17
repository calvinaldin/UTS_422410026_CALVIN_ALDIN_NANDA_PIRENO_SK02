@extends('layouts.app')
@section('title', 'Tambah Perlengkapan')

@section('content')
<x-page-header title="Tambah perlengkapan" />
<form action="{{ route('perlengkapan.store') }}" method="POST" novalidate>
    @csrf
    @include('perlengkapan._form')
</form>
@endsection
