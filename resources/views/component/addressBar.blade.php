
<script type="text/x-template" id="template_addresBar">
  <div class="myContainer">
    <div class="img-background">
      <img src="/img/imag1.jpg" alt="" v-show='!formShow'>


    <form class="" action="{{route('apt.store')}}" method="post" enctype="multipart/form-data">
      @csrf
      @method('POST')

      <div class="center_on_page" v-show='!formShow'>
        <label class="white" for="address">Dove si trova l Appartamento? </label>
        <input type="text" name="address" value="" v-model="user_id">
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
            <textarea name="description" rows="8" cols="80"></textarea>
          </div>

          <div class="info col-md-4 form-group">
            <h2>Informazioni:</h2>
            <label for="mq">Dimensioni</label>
            <input type="text" name="mq" value="">

            <label for="rooms">Numero di camere</label>
            <input type="text" name="rooms" value="">

            <label for="beds">Posti letto</label>
            <input type="text" name="beds" value="">

            <label for="bathrooms">Numero di bagni</label>
            <input type="text" name="bathrooms" value="">
          </div>

        </section>

        <section class="row">

          <div class="servizi col-md-6 col-sm-12 form-group">
            <label for="feature"><h1>Servizi disponibili</h1></label>
            <ul>
              @foreach ($features as $feature)
                <li><input type="checkbox" name="feature[]" value="{{$feature -> id}}">{{$feature-> type}}</li>
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

        user_id: this.id,
        address_added: false
      }
    },

    props: {

      id: Number
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

        console.log(this.address_added);
      },
    }

  });

</script>
