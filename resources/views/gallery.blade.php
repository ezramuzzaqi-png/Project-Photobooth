@extends('layouts.app')

@section('title', "Galeri Layout — HOLD' MOMENT")
@section('meta-description', "Pilih dari berbagai template photostrip — format 2x2, 2x3, strip panjang, hingga struk.")

@section('content')
<x-layout-selection :templates="$templates" />
@endsection