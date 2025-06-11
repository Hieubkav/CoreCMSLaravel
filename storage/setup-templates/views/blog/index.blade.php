@extends('layouts.shop')

@section('title', 'Blog - ' . ($globalSettings->site_name ?? 'Website'))

@section('content')
<livewire:blog-index />
@endsection
