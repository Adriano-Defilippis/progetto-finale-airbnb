@extends('layouts.base')

@section('content_header')

  <header class="row col-md-12 header-absolute  color-white">
    {{-- <div class=""> --}}
      <div class="logo col-md-4 col-xs-10">
        <a class="col-md-2 col-xs-1"href="{{route('index')}}"><img src="air.jpg" alt="boolbnb logo"></a>
      </div>

      <ul class="navBar col-md-8">


      @guest
        @if (Route::has('login'))
          <li><a href="{{ route('login') }}">Login</a></li>
        @endif

        @if (Route::has('register'))
            <li><a href="{{ route('register') }}">Register</a></li>
        @endif

        @else

          <li> <a href="{{route('home')}}">{{ Auth::user()->lastname . ", " . Auth::user()->firstname }}</a>  </li>
          <li>  <a href="{{route('logout')}}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
            Logout
          </a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @endguest
        </ul>
  </header>

@endsection

@section('content')

  <div class="img-background">
    <img src="img/imag1.jpg" alt="">

    <section class="row">
      <div class="inputApparts col-lg-5 col-md-8 col-sm-12">
        <div class="">
          Inserisci parametri di ricerca:
        </div>
        <div class="">
          <form class="" action="{{route('apt.index')}}" method="get">
            @csrf
            @method('GET')
            <div class="">
              <label for="beds">Posti letto: </label>
              <input type="text" name="beds" value="1">
            </div>
            <div class="">
              <label for="rooms">N. stanze</label>
              <input type="text" name="rooms" value="1">
            </div>
            <div class="">
              <label for="bathrooms">N. Bagni</label>
              <input type="text" name="bathrooms" value="1">
            </div>
            <div class="">
              <label for="feature"><h3>Servizi disponibili</h3></label>
              <ul>
                @foreach ($features as $feature)
                  <li><input type="checkbox" name="features[]" value="{{$feature -> id}}">{{$feature-> type}}</li>
                @endforeach
              </ul>
            </div>

            <div class="">
                <button type="submit">Vai</button>
            </div>

          </form>
        </div>
        {{-- <h1>Cerca gli appartamenti nella Tua zona.</h1>
        <form class="" action="index.html" method="post">
          <h3>Dove: </h3>
          <input class="position" type="text" name="position" value="" placeholder="Ovunque">
          <!-- <input class="positionButt" type="button" name="" value="Vai"> -->
          <h3>Quante persone: </h3>
          <input class="position" type="text" name="position" value="" placeholder="1">
          <!-- <input class="positionButt" type="button" name="" value="Vai"> -->
          <h3>In data: </h3>
          <input class="position" type="text" name="position" value="" placeholder="01-11-2019">
          <!-- <input class="positionButt" type="button" name="" value="Vai"> -->
          <button class ="searchGo" type="button" name="button">Avvia la ricerca</button>
        </form> --}}
      </div>
    </section>
  </div>

  <div class="apartments-wrapper">
    <ul class="row">
      <h1 class="col-md-12">Gli appartamenti in evidenza</h1>
      @foreach ($apts as $apt)
        <li class="col-md-4 col-sm-6">{{$apt->description}}
           <br>
          Proprietario: {{$apt->user->lastname}}
          <br>
          <a href="{{route('apt.show', $apt->id)}}">Visualizza</a>
        </li>
      @endforeach
    </ul>

  </div>


@endsection
