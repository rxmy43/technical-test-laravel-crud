@extends('layouts.app')

@section('title', 'Users')
@section('header', 'User Management - Create New')

@section('content')
    <div class="container mx-auto max-w-6xl pl-4">
        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-100 border border-green-300 px-4 py-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash Error Message --}}
        @if (session('error'))
            <div class="mb-6 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection