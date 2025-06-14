@extends('layouts.shop')

@section('title', 'Đội ngũ nhân viên - ' . ($globalSettings->site_name ?? 'Website'))

@section('content')
<livewire:staff-index />
@endsection
