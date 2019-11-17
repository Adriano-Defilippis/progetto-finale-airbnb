<header class="row col-md-12">

    <div class="logo col-md-4 col-xs-10">
      <a class="col-md-2 col-xs-1"href="{{route('index')}}"><img src="{{asset('air.jpg')}}" alt="boolbnb logo"></a>
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
