@extends('layouts.app')

@section('content')
<div class="container" id="dashboard">
    <dashboard></dashboard>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('/assets/js/dashboard.js') }}"></script>
@endsection
