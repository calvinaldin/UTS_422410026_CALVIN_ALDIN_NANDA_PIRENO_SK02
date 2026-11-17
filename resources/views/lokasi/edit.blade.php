@extends('layouts.app')
@section('title', 'Edit Lokasi')

@section('content')
<x-page-header title="Edit detail lokasi" :subtitle="$lokasi->nama_lokasi" />
<form action="{{ route('lokasi.update', $lokasi) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    @include('lokasi._form')
</form>
@endsection
