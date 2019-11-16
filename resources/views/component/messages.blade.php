<script type="text/x-template" id="template_messages">



  <div class="box">

    <div class="card">
      <div class="card-header">
        <ul class="nav nav-pills card-header-pills">



        </ul>
      </div>
      <div class="card-body messages">

    <!-- Form per invio messaggio  -->
    @if (Auth::id() != $apt->user_id || Auth::guest())
    <form class="col-md-12" action="{{ route('msg.guest.create', $apt-> id ) }}" method="post" >
      @csrf
      @method('POST')

      <div class="card text-white bg-dark mb-3">

        <div class="card-header row">
            <label class="col-md-3 col-sm-12" for="sender_email" name="sender_email">Inserisi la tua mail</label>
            <input class="col-md-8 col-sm-12" type="email" name="sender_email" v-model="email"/>
        </div>

        <div class="card-body">

          <div class="row">
            <h5 class="card-title col-md-3">Stai contattando:</h5>
            <p class="col-md-8">{{$apt->user->firstname}} {{$apt->user->lastname}}</p>
          </div>
          <div class="row">
            <h5 class="card-title col-md-3">Appartamento:</h5>
            <p class="col-md-8">{{$apt->address}}</p>
          </div>


          <label for="body" name="body"><p class="card-text">inserisci il tuo messaggio:</p></label><br>
          <textarea class="col-md-12" type="text" name="body" v-model="textarea">@{ textarea }</textarea>
          <br>

          <div class="text-right col-md-12 row">
            <input type="button" class="btn btn-primary" value="Invia il Messaggio" @click="saveMsg()"/>

          </div>

        </div>
      </div>
     </form>
    @endif

      @if (Auth::id() == $apt->user_id)
      <div class="col-md-12" v-show="!isAbilitedForm" >
        @foreach ($apt -> messages as $message)
          <div class="card text-white bg-dark mb-3 col-lg-12">
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
