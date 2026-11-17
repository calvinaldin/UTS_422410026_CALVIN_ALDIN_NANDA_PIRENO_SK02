@extends('layouts.app')
@section('title', 'Edit Sesi')

@section('content')
<x-page-header :title="'Edit sesi '.$sesi->kode_sesi" subtitle="Ubah data sesi, lokasi, atau perlengkapan yang digunakan." />
<form action="{{ route('sesi-pengamatan.update', $sesi) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    @include('sesi._form')
</form>
@endsection
