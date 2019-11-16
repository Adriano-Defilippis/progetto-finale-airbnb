@extends('layouts.base')

@section('content_header')

  @include('component.header')

@endsection

@section('content')

  @include('component.messages')


  @php
    use App\Tier;
    use App\Payment;

    $tiers = DB::table('tiers')->where('level', '>', 0)->orderBy('level', 'asc')
                ->get();

                // echo $tiers;
  @endphp

  <main>

    <div class="photos col-md-12">
      <img class="otherjpg col-md-12" src="/img/uploads/{{$apt->img_path}}" alt="Foto dell'appartamento">
    </div>

    {{-- section 2 sotto immagine --}}
    <section class="row">
      {{-- Col Descrizione --}}
      <div class="card desc col-md-8 col-sm-12">
        <h5 class="card-header">Descrizione:</h5>
        <div class="card-body">
          <h5 class="card-title">Cosa ti racconto...</h5>
          <p class="card-text">{{$apt->description}}</p>
        </div>
      </div>

      {{-- Col Info appartamento --}}
      <div class="info col-md-4 col-sm-12">

        <ul class="list-group info col-md-12">
          <div class="card-header">
            <h3>Informazioni</h3>
          </div>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Dimensioni:
            <span class="">mq</span>
            <span class="badge badge-primary badge-pill">{{$apt->mq}}</span>
          </li>

          <li class="list-group-item d-flex justify-content-between align-items-center">
            Numero di camere:
            <span class="badge badge-primary badge-pill">{{$apt->rooms}}</span>
          </li>

          <li class="list-group-item d-flex justify-content-between align-items-center">
            Posti letto:
            <span class="badge badge-primary badge-pill">{{$apt->beds}}</span>
          </li>

          <li class="list-group-item d-flex justify-content-between align-items-center">
            Numero di bagni:
            <span class="badge badge-primary badge-pill">{{$apt->bathrooms}}</span>
          </li>

          <li class="list-group-item d-flex justify-content-between align-items-center">
            Indirizzo:
            <span class="badge badge-primary badge-pill">{{$apt->address}}</span>
          </li>

          @if(Auth::id()==$apt->user->id)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Visualizzazioni: {{$apt-> visualizations}} <br>
            <a href="{{route('apt.edit', $apt->id)}}">Modifica...</a> <br>
            <a href="{{route('apt.destroy', $apt->id)}}">!!ELIMINA!!</a> <br>
          </li>
          @endif

        </ul>

      </div>

    </section>

    {{-- Section 3 --}}
    <section class="row">
      {{-- Colonna servizi appartamento --}}
      <div class="servizi col-md-6 col-sm-12">
        <div class="card servizi col-md-6 col-sm-12">
          <h5 class="card-header">Servizi disponibili</h5>
          <div class="card-body">

              @foreach ($apt -> features as $feature)
                <p>{{ $feature -> type }}</p>
              @endforeach

          </div>
        </div>


      </div>

      {{-- Colonna contatti --}}
      <div class="contact col-md-6">

        <div id="vue_messages">
          <messages
                    :apt_id= "{{ $apt->id }}"
          ></messages>
        </div>

      </div>

    </section>

    <section class="row">
      <div id="vue_payment" class="col-md-6 col-sm-12">
        @include('component.payment')
      </div>

    </section>



  </main>


@endsection
