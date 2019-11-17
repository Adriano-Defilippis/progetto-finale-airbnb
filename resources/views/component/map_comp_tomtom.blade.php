<script type="text/x-template" id="template_map">



   <div class="">
      <div class="card-header">
        <h3 class="card-title">@{{ address }}</h3>
      </div>
      <div class="card">
        <img id="path_map" v-bind:src="mapImg" class="card-img" alt="map_TOM_TOM">
        <div class="card-img-overlay">
          <p class="card-text">@{{ msgDefault }}</p>
        </div>
        <span v-show="mapExists" class="pointer">
          <i class="fas fa-map-marker fa-2x"></i>
        </span>
      </div>
    </div>


</script>



<script type="text/javascript">

var token = $('meta[name="csrf-token"]').attr('content');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

  Vue.component('map-tom-tom', {
    template: '#template_map',

    data: function(){

      return {

        address: this.apt_address,
        mapImg: "{{ asset('/img/img_front/map_default.png') }}",
        msgDefault: "Nessuna Mappa Disponibile",
        thisAptId: this.apt_id,
        mapExists: false
      }

    },

    props: {

      apt_address : String,
      apt_id: String
    },

    mounted() {

      this.axiosMap()

    },

    created() {



    },


    computed: {


    },

    methods: {




      axiosMap(){

        // var thisAddress = this.address;

        console.log("axiosMap");
        axios.post(`/map/` + this.thisAptId, {address: this.apt_address, responseType: 'arraybuffer'})
          .then(response => {

            var objAddress = response.data.body.results[0].address;
            // Assegno il nome corretto di indirizzo
            this.address = objAddress.freeformAddress;
            // Cambio messaggio nessuna mappa con:
            this.msgDefault = "MAPPA";

            if (response.data.filename) {

              // Creo la src per immagine mappa
              this.mapImg = '/img/' + response.data.filename + '';
              this.mapExists = true;
            }

            console.log('map tom tom response', response, response.data.filename, this.mapImg);
          })
          .catch(e => {
            console.log("errors", e);
          })
          
      }


    },

    watch:{



    }

  });

</script>
