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
            <li><a href="{{ route('register') }}">Registrati</a></li>
        @endif

        @else

          <li> <a href="{{route('home')}}">Benvenuto, {{ Auth::user()->lastname}}</a>  </li>
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
          <form class="" action="{{route('apt.index')}}" method="get">
            <div class="loc-input">
              <label for="query">Cerca una località: </label>
              <input type="text" name="query" value="">
            </div>
            <div class="">
              <label for="beds">Posti letto: </label>
              <input type="number" name="beds" value="1" required>
            </div>
            <div class="">
              <label for="rooms">N. stanze: </label>
              <input type="number" name="rooms" value="1" required>
            </div>
            <div class="">
              <label for="bathrooms">N. Bagni: </label>
              <input type="number" name="bathrooms" value="1" required>
            </div>
            <div class="">
              <label for="feature"><h3>Servizi disponibili</h3></label>
              <ul class="feature-list">
                @foreach ($features as $feature)
                  <li class="feature-item"><input type="checkbox" name="features[]" value="{{$feature -> id}}">{{$feature-> type}}</li>
                @endforeach
              </ul>
            </div>

            <div class="">
                <button class="submit-btn" type="submit">Vai</button>
            </div>

          </form>
        </div>

      </div>
    </section>
  </div>

  <div class="apartments-wrapper">


      @if ($apts)
        <h1 class="col-md-12 text-center">In evidenza</h1>
        <div class="flex-container">

          @foreach ($apts as $apt)
            <div class="apt-card">
              <div class="card-img">

                <img src=
                @if ($apt->img_path)
                  "{{asset($apt->img_path)}}"
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
      @else
        <h1 class="col-md-12 text-center">Nessun risultato...</h1>
      @endif

  </div>


@endsection
