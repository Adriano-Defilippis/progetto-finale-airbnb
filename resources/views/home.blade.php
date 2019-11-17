@extends('layouts.base')
@php
  use App\Apartment;
  $apts = Apartment::where('user_id', Auth::id())
          ->orderBy('id', 'desc')
          ->get();

@endphp
@section('content_header')
  @include('component.header')
@endsection


@section('content')

<div class="apartments-wrapper">
  <div class="flex-container">

    <form class="new-apt-form" action="{{route('apt.create')}}">
        <input class="new-apt-button" type="submit" value="+" />
    </form>

    @foreach ($apts as $apt)
      <div class="apt-card">
        <div class="card-img">

          <img src=
          @if ($apt->img_path)
            "/img/uploads/{{$apt->img_path}}"
          @else
            "{{asset('img/ap1.jpg')}}"
          @endif
           alt="foto appartamento">

        </div>
        <div class="card-desc">
          {{ strlen($apt->description) > 100 ? substr($apt->description, 0, 100) . '...' : $apt->description }}

        </div>
        <form action="{{route('apt.show', $apt->id)}}">
            <input type="submit" value="Visualizza" />
        </form>
      </div>
    @endforeach

  </div>


</div>
@endsection
