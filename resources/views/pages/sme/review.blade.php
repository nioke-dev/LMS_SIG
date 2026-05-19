@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Review Blueprint & Submit Materi')

@section('page-title', 'Blueprint Review')

@section('content')
    @livewire(\App\Livewire\Sme\ReviewBlueprint::class, ['blueprint' => $blueprint])
@endsection
