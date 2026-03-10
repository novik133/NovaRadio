@extends('admin.layout')

@section('title', __('admin.pages.create'))

@php
$page = new \App\Models\Page();
@endphp

@include('admin.pages.form')
