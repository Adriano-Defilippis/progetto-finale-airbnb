<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.4.2/handlebars.js"></script>
<script type="text/x-template" id="template_payments">

  <div class="">

  <!-- Info Piano Sottoscrizione -->
  <div class="" id="paymeffnt-form"  method="post" v-if="!showPayBrayntree">

    <div class="list_group">
      <div class="card-header">
          <h3 class="card-title">Sponsorizzazioni</h3>
      </div>
      <div class="list_group_item">
        <h5 class="card-title" v-if="!seenSubsBtn">Sponsorizzazioni del tuo appartamento</h5>
        <h5 class="card-title" v-if="seenSubsBtn">Nessuna Sponsorizzazione attiva</h5>

        <!-- Sponsorizzazioni attive -->
        <div class="table-responsive-lg" v-if="!seenSubsBtn">
          <table class="table table-hover">
            <thead class="thead-light">
              <tr v-for="tier in tierActive">
                <th scope="col"  v-for="(value, name) in tier">@{{ name }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="tier in tierActive">
                <th scope="row" v-for="(value, name) in tier">@{{ value }}</th>
              </tr>
            </tbody>
          </table>
        </div>


        <!-- Tabella delle sponsorizzazioni -->
        <div class="table-responsive-lg" v-show="show_form">
          <table class="table table-hover" >
            <thead class="thead-light">
              <tr>
                <th scope="col">Level</th>
                <th scope="col">Price</th>
                <th scope="col">Duration</th>
              </tr>
            </thead>
            <tbody v-for="one in resultsTiers">
              <tr>
                <th scope="row" v-for="(value, name) in one">@{{ value }}</th>
                <td>
                  <input class="checkbox_tier" type="checkbox" name="tier_id" :checked="active" :value="one.id" @click="check($event)"/>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <p class="card-text" v-if="!seenSubsBtn">@{{ results.msg_subs }}</p>

        <a class="btn btn-primary"  @click="setShowForm()" v-if="seenSubsBtn">Sottoscrivi un piano!</a>
        <a class="btn btn-primary"  @click="getFormPayment()" v-if="activePayBtn">Procedi con il pagamento!</a>
      </div>
      <div class="card-footer text-muted">
        Last Payment: @{{ results.diff }}
      </div>
    </div>



  </div>

  <!-- BRAINTREE Paiment -->
  {{-- layout pagamento --}}
  <form id="payment-form" class="col-lg-6 col-sm-12">

    <section v-if="showPayBrayntree">
      <div class="bt-drop-in-wrapper">
          <div id="bt-dropin"></div>
      </div>

      <input id="nonce" name="payment_method_nonce" type="hidden" v-model="payment_method_nonce" />
      <p @click="turnBack()"><==Indietro</p>
      <button class="button" @click="braintreeScript()"><span>Test Transaction</span></button>
    </section>


  </form>

</div>

</script>



<script type="text/javascript">

var token = $('meta[name="csrf-token"]').attr('content');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

  Vue.component('payments', {
    template: '#template_payments',

    data: function(){

      return {
        showForm: false,
        this_address: this.address,
        token_payment: "",
        tiers: null,
        showInfo: true,
        showPayForm: false,
        showPayBrayntree: false,
        tierActive: this.tier_active,
        seenSubsBtn : true,
        msgSubscription : "",
        lastPayment: "",
        results: [],
        active: false,
        resultsTiers: [],
        show_form: false,
        activePayBtn: false,
        payment_method_nonce: '',
        selected_tier: this.selected_tier_pay,

      }

    },

    props: {

      // apt2_id: Number,
      // msg: String,
      address: String,
      apt_id: Number,
      user_id: Number,
      tier_active: Array,
      payments_story: Array,
      tier_id: Number,
      selected_tier_pay: Number

    },

    mounted() {
      // axios.get('/index/' + this.apt_id)
      // .then( (res) => {
      //
      //   this.msgSubscription = res.data.msgSubscription;
      //   this.results = res.data;
      //
      //
      //   console.log("mounted response" , res.data, this.msgSubscription, res.data.tier_id_apt);
      // })
      // .catch( error => { console.log(error); });
      // console.log("mounted" , this.results);
      this.firstAxios();

    },

    created() {

      // this.returnMsgSubscription;
      //console.log("created component" + this.msgSubscription);
      if (this.tier_id > 1) {
        this.seenSubsBtn = false;
      }
      //console.log(this.seenSubsBtn);

    },
  //

    computed: {

      // Ritorno msg stringa da Array results, da chiamata axios
      returnMsgSubscription: function () {

        //console.log("ciao", this.results.msg_subs);
        return this.results.msg_subs;
      },

    },

    methods: {


    resetShowBool(){
      this.showForm= false;
      this.showInfo= true;
      this.showPayForm= false;
      this.showPayBrayntree= false;
      this.seenSubsBtn = false;
      this.show_form= false;
      this.activePayBtn= false;
    },

    firstAxios(){

      axios.get('/index/' + this.apt_id)
      .then( (res) => {

        this.msgSubscription = res.data.msgSubscription;
        this.results = res.data;


        //console.log("mounted response" , res.data, this.msgSubscription, res.data.tier_id_apt);
      })
      .catch( error => { console.log(error); });
      //console.log("mounted" , this.results);
    },

    function(e){ this.returnMsgSubscription },

    // Funzione per lanciare script Baintree
    braintreeScript(e){

        var form = document.querySelector('#payment-form');
        //Se occorre registrare i metodi di pagamento, bisogna creare customer_id (Id braintree) su tabella user
        var client_token = "sandbox_8hrkkqn6_dh357c6zpkqxdvm9"; // PER TEST, TOKEN STATICO ACCOUNT BRAINTREE
        var tier_for_braintree = this.selected_tier;
        var apartment_id = this.apt_id;

        //TODO nascondere nel back end
        // var data = new FormData();
        // data.append( 'tier_id', this.selected_tier);
        console.log("dataaaaaaa", this.selected_tier, tier_for_braintree);

        braintree.dropin.create({
          authorization: client_token,
          selector: '#bt-dropin',
          paypal: {
            flow: 'vault'
          }
        }, function (createErr, instance) {
          if (createErr) {
            console.log('Create Error', createErr);
            return;
          }
          form.addEventListener('submit', function (event) {
            event.preventDefault();


            // Abilito form pagamento
            this.showPayBrayntree = true;

            instance.requestPaymentMethod(function (err, payload) {
              if (err) {
                console.log('Request Payment Method Error', err);
                return;
              }
              // Add the nonce to the form and submit
              document.querySelector('#nonce').value = payload.nonce;
              this.payment_method_nonce = payload.nonce;
              console.log("methodnonce", this.payment_method_nonce);

              this.showPayBrayntree = false;
              var data = new FormData();
              // Axios
              data.append( '_token',token);
              data.append( 'payment_method_nonce', this.payment_method_nonce);
              data.append( 'tier_id', tier_for_braintree);
              data.append( 'apartment_id', apartment_id)
            //  console.log("tier_idddddddd", tier_for_braintree, data);

              axios.post('/payment/' + this.apt_id,
                          data,

                           {
                            onUploadProgress: uploadEvent => {

                              console.log(this.payment_method_nonce);
                              console.log('upload Progress ' + Math.round(uploadEvent.loaded / uploadEvent.total * 100)  + "%");
                            }
              })
                .then(res => {

                  //console.log("res.data",res, paymentShow);
                });
            });

          });
        });

          //console.log("sono baintreeee");
    },

      // Funzione bottone back
      turnBack(){

        this.showPayBrayntree = false;
        this.show_form = true;

      //  console.log("turnback", this.showPayBrayntree);
      },



      // Funzione per check unico checkbox
      check(e){
        if (e.target.checked) {

          // Attivo il bottone pagamento se ho cliccato
          //su almenno una check box
          this.activePayBtn = true;
          // Gruppo delle check box
          var x = document.getElementsByClassName("checkbox_tier");
          var i;
          // Disattivo il Check su tutti gli elementi
          for (i = 0; i < x.length; i++) {

            x[i].style.backgroundColor = "red";
            x[i].checked = false;

          //  console.log("Set all false", x[i]);
          }

          // Setto attributo check su elemento cliccato
          e.target.checked = true;

          // Assegno al Form il valore id tier cliccato
          this.selected_tier = e.target.value;

          //console.log("event.target", this.selected_tier,  e.target.value);

          // se non ho nessuna box attiva
        }else if(!e.target.checked){
           //Disattivo il bottone modulo pagamento
            this.activePayBtn = false;
        }

      },

      setShowForm(){

        this.show_form = !this.show_form;
        //console.log(this.show_form);

        this.getTableTiers();
      //  console.log("results tiers from click" , this.resultsTiers);
      },


      getTableTiers(){

        //console.log("ciao");

        axios.get('/tiers/' + this.apt_id)
        .then( (res) => {

          this.resultsTiers = res.data.tiers;
        //  console.log("response get tiers", res, this.resultsTiers);
        })
        .catch( error => { console.log(error); });

      },


      getFormPayment(){

        this.showPayForm = !this.showPayForm;
        // Mostro il form per inserimento dati carta di credito
        this.showPayBrayntree = true;
        console.log(this.showPayBrayntree , "tier selected", this.selected_tier);
        var success =false ;
        // this.showPayBrayntree = true;
        var user_data = {

          _token: token,
          apartment_id: this.apt_id
        };
        //console.log(user_data);
        //
        axios.get('/payment/' + this.apt_id, user_data)
          .then(function(res){

            // Salvo il token per la sessione braintree nella variabile in data
            // this.token_payment = res.data['token-braintree'];
            // Salvo i valori di ritorno della tabella tiers in un ARRAY
            // this.tiers = res.data['tiers'];
            success =true;
          //  console.log("success", success);
            //console.log("res get form payment",res);
          })
          .catch(function(err){
            console.log(err);
          });

          this.braintreeScript();
          //console.log("ci22222ao", success);

      }

    },

    watch:{

      // 'msgSubscription': function(val){
      //
      //   this.msgSubscription = val ;
      //  },

      'payment_method_nonce': function(val){

          this.payment_method_nonce = val;
          //console.log("log di watch payment method nonce",this.payment_method_nonce);
       },

       'selected_tier': function(val){

           this.selected_tier = val;

        },



    }

  });

</script>
