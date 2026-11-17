@extends('layouts.app')
@section('title', 'Tambah Lokasi')

@section('content')
<x-page-header title="Tambah detail lokasi" subtitle="Hubungkan sebuah lokasi pengamatan ke sesi yang belum memilikinya." />
<form action="{{ route('lokasi.store') }}" method="POST" novalidate>
    @csrf
    @include('lokasi._form')
</form>
@endsection
