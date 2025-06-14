@extends('layouts.shop')

@section('title', 'Dịch vụ - ' . ($globalSettings->site_name ?? 'Website'))

@section('content')
<livewire:service-index />
@endsection
