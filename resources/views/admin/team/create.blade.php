@extends('admin.layout')

@section('title', 'Add Team Member')

@php
$member = new \App\Models\TeamMember();
@endphp

@include('admin.team.form')
