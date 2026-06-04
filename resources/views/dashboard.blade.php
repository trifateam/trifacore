@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-500">
            Welcome back, {{ auth()->user()->name ?? 'User' }}!
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
        <p class="text-gray-600">
            Ini adalah halaman Dashboard placeholder. Layout utama dengan Sidebar dan Navbar telah berhasil dimuat!
        </p>
    </div>
</div>
@endsection
