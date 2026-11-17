@extends('layouts.app')
@section('title', 'Tambah Sesi')

@section('content')
<x-page-header title="Tambah sesi pengamatan" subtitle="Isi data sesi, lokasi, dan pilih perlengkapan. Semua tersimpan dalam satu transaksi." />
<form action="{{ route('sesi-pengamatan.store') }}" method="POST" novalidate>
    @csrf
    @include('sesi._form')
</form>
@endsection
