@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Review Blueprint')

@section('page-title', 'Review Blueprint')

@section('content')
    @livewire(\App\Livewire\Sme\BlueprintList::class)
@endsection
