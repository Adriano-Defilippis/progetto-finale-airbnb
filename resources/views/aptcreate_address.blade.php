@extends('layouts.base')

@section('content_header')

  @include('component.header')

@endsection

@section('content')
  @include('component.addressBar')

  <div id="app">
    <addapartment
            :id="{{ Auth::id() }}"
    ></addapartment>
  </div>
@endsection
