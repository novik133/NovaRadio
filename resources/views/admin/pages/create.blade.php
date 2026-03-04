@extends('admin.layout')

@section('title', 'Create Page')

@php
$page = new \App\Models\Page();
@endphp

@include('admin.pages.form')
