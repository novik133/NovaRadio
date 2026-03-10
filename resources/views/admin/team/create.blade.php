@extends('admin.layout')

@section('title', __('admin.team.create'))

@php
$member = new \App\Models\TeamMember();
@endphp

@include('admin.team.form')
