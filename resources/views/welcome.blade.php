@extends('layouts.app')

@section('title', 'Welcome')

@section('header', 'Welcome')

@section('content')
    <div class="text-center max-w-2xl mx-auto mt-12">
        <h1 class="text-4xl font-bold text-gray-800 leading-tight">
            Welcome to the User Management API Demo
        </h1>
        <p class="mt-4 text-lg text-gray-600">
            This technical test is submitted as part of the assessment for <br>
            <span class="font-medium text-purple-700">PT Rimba Ananta Vikasa Indonesia</span>.
        </p>

        <div class="mt-6">
            <a href="{{ route('users.index') }}"
                class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg shadow hover:bg-purple-700 transition">
                View User Management
            </a>
        </div>
    </div>
@endsection