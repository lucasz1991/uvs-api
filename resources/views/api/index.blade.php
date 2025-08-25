@extends('layouts.master')
@section('title')
    API Tokens
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">

                <div>
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        @livewire('api.api-token-manager')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
