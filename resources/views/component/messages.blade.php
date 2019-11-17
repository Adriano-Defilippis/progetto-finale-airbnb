<script type="text/x-template" id="template_messages">



    <!-- Form per invio messaggio  -->
    @if (Auth::id() != $apt->user_id || Auth::guest())
    <form class="col-md-12" action="{{ route('msg.guest.create', $apt-> id ) }}" method="post" >
      @csrf
      @method('POST')

      <div class="card">

        <div class="card-header">
            <label class="col-lg-12" for="sender_email" name="sender_email">Inserisci la tua mail</label>
            <input class="col-lg-12" type="email" name="sender_email" v-model="email"/>
        </div>


        <div class="card-body">

          <div class="row">
            <h5 class="card-title col-lg-4 col-sm-12">Stai contattando:</h5>
            <p class="col-lg-8 col-sm-12">{{$apt->user->firstname}} {{$apt->user->lastname}}</p>
          </div>
          <div class="row">
            <h5 class="card-title col-lg-4 col-sm-12">Appartamento:</h5>
            <p class="col-lg-8 col-sm-12">{{$apt->address}}</p>
          </div>


          <div class="row">
            <label for="body" name="body" class="col-lg-12"><p class="card-text">inserisci il tuo messaggio:</p></label><br>
            <textarea class="col-md-12" type="text" name="body" v-model="textarea">@{ textarea }</textarea>
          </div>
          <br>

          <div class="text-right col-md-12">
            <input type="button" class="btn btn-primary" value="Invia il Messaggio" @click="saveMsg()"/>
          </div>

        </div>
      </div>
     </form>
    @else

      @if (Auth::id() == $apt->user_id & count($apt -> messages)>0)
      <div class="card">
        <div class="servizi">
          <div class="card-header">
            <h3 class="">Messaggi ricevuti</h3>
          </div>
      <div class="card-body messages">
      <div class="col-md-12" v-show="!isAbilitedForm" >
        @foreach ($apt -> messages as $message)
          <div class="card col-lg-12">
            <div class="card-header">Messaggio da: {{$message -> sender_email}}</div>
            <div class="card-body">
              <h5 class="card-title">Appartamento: {{$apt->address}}</h5>
              <p class="card-text">{{$message -> body}}</p>
            </div>
          </div>
        @endforeach
      </div>
      @endif


    </div>
  </div>
  @endif

  </div>

</script>


<script type="text/javascript">

var token = $('meta[name="csrf-token"]').attr('content');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;


  Vue.component('messages', {

    template: '#template_messages',

    data: function(){

      return {

        isAbilitedForm: false,
        isAbilitedMessages: true,
        active_btn_msg: 'active',
        active_btn_form: 'disable',
        textarea: '',
        email: ''
      }
    },

    props: {

      id: Number,
      apt_id: Number
    },

    computed: {

      show(){
        return this;
      },


    },

    methods: {

      setAbilitedForm(){

        this.isAbilitedForm = true;
        this.isAbilitedMessages = false;

        this.active_btn_form = 'active';
        this.active_btn_msg = 'disable';
      },

      setAbilitedMessages(){

        this.isAbilitedForm = false;
        this.isAbilitedMessages = true;

        this.active_btn_msg = 'active';
        this.active_btn_form = 'disable';
      },

      saveMsg(){

        var msg = {

          _token: token,
          body: this.textarea,
          sender_email: this.email,
          apartment_id: this.apt_id
        };


        console.log(this.textarea);
        axios.post('/message/create/' + this.apt_id, msg)
          .then(function(res){

            this.textarea = "";
            this.email = "";

            console.log(res);
          })
          .catch(function(err){
            console.log(err);
          });

          console.log(msg);

      }



    },

    watch:{

      'email': function(val){

        this.email = val;
        console.log(this.email);
      },

      'textarea': function(val){

        this.textarea = val;
        console.log(this.textarea);
      },



    }
  });

</script>
