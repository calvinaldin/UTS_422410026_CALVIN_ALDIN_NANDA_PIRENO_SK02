@extends('layouts.app')
@section('title', 'Edit Perlengkapan')

@section('content')
<x-page-header title="Edit perlengkapan" :subtitle="$perlengkapan->kode" />
<form action="{{ route('perlengkapan.update', $perlengkapan) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    @include('perlengkapan._form')
</form>
@endsection
