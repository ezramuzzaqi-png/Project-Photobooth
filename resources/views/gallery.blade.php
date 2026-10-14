@extends('layouts.app')

@section('title', "Galeri Template — HOLD' MOMENT")

@push('styles')
<style>
    body {
        background: linear-gradient(to bottom, #ffedd5 0, #fff7ed 340px, #F6F4EE 680px) !important;
    }
</style>
@endpush

@section('content')
<x-layout-selection :templates="$templates" />
@endsection
