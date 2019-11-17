
<script type="text/x-template" id="template_addresBar">
  <div class="myContainer">
    <div class="img-background">
      <img src="{{asset('img/imag1.jpg')}}" alt="" v-show='!formShow'>


    <form class="apt-form" v-bind:action="action_route" method="POST" enctype="multipart/form-data">
      @csrf
      @method('POST')

      <div class="center_on_page" v-show='!formShow'>
        <label class="white" for="address">Dove si trova l'appartamento? </label>
        <input id="address-field" type="text" name="address" required>
        <input  @click="setFormShow()" type="button" value="GO"></input>
      </div>

      <main v-show='formShow'>

        <div class="photos col-md-12">
          <!-- <label for="img"></label> -->
          <br>
          <input name="img" accept="image/*" type="file">
        </div>

        <section class="row">

          <div class="desc col-md-8 form-group">
            <label for="description"><h2>Inserisci qui una breve descrizione:</h2></label>
            <textarea v-bind:value="prev_description" name="description" rows="8" cols="80" required></textarea>
          </div>

          <div class="info col-md-4 form-group">
            <h2>Informazioni:</h2>
            <label for="mq">Dimensioni</label>
            <input type="number" name="mq" v-bind:value="prev_mq" required>

            <label for="rooms">Numero di camere</label>
            <input type="number" name="rooms" v-bind:value="prev_rooms" required>

            <label for="beds">Posti letto</label>
            <input type="number" name="beds" v-bind:value="prev_beds" required>

            <label for="bathrooms">Numero di bagni</label>
            <input type="number" name="bathrooms" v-bind:value="prev_bathrooms" required>
          </div>

        </section>

        <section class="row">

          <div class="servizi col-md-6 col-sm-12 form-group">
            <label for="feature"><h1>Servizi disponibili</h1></label>
            <ul>
              @foreach ($features as $feature)
                <li><input class="feature-checkbox"
                            type="checkbox"
                            name="feature[]"
                            value="{{$feature -> id}}"
                      @isset($apt)
                        @foreach ($apt->features as $value)
                          @if ($value['id'] == $feature->id)
                            checked
                          @endif
                        @endforeach
                      @endisset
                            >
                            {{$feature-> type}}
                          </li>
              @endforeach
            </ul>
          </div>

          <div class="col-md-6 col-sm-12" v-show='formShow'>
            <input type="submit" value="Invia">
          </div>

        </section>

      </main>


    </form>
    </div>
  </div>
</script>



<script type="text/javascript">

  Vue.component('addapartment', {

    template: '#template_addresBar',

    data: function(){

      return {
        action_route: this.route,
        prev_address: this.address,
        prev_description: this.description,
        prev_rooms: this.rooms,
        prev_beds: this.beds,
        prev_mq: this.mq,
        prev_bathrooms: this.bathrooms,
        address_added: false,

      }
    },

    props: {

      address: String,
      description: String,
      route: String,
      rooms: String,
      beds: String,
      mq: String,
      bathrooms: String,

    },

    computed: {

      formShow(){

        if (this.address_added) {

          return this
        }
      },

    },

    methods: {

      setFormShow(){

        this.address_added = !this.address_added;

      },
    }

  });

</script>
