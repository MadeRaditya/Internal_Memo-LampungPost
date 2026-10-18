@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-3">
    <h2 class="text-xl font-bold text-gray-800">Dashboard Internal Memo</h2>
    <p class="text-sm text-gray-600">Halo <strong>{{ auth()->user()->name }}</strong>, Anda berhasil masuk sebagai <strong>{{ auth()->user()->role }}</strong> dari divisi <strong>{{ auth()->user()->department->name ?? '-' }}</strong>.</p>
</div>
@endsection