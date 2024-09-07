@extends('layouts.master')


@section('page_title', ' Verify')


@section('content')


@include('partials.alerts')
@if(session('student'))
    {!! session('student') !!}
@else
    {!! $emptyHtml !!}
@endif
<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('rfid_field').focus();
        });
</script>

@endsection