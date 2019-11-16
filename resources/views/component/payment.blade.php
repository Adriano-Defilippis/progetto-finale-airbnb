{{-- layout pagamento --}}
<form id="payment-form" action="{{route('payment.send', $apt->id)}}" method="post">
  @csrf
  @method('POST')

  <div class="table-responsive-lg">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th scope="col">Level</th>
          <th scope="col">Price</th>
          <th scope="col">Extension Time</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        @foreach ($tiers as $tier)
          <tr>
            <th scope="row">{{$tier->level}}</th>
            <td>{{$tier->price}}</td>
            <td>{{$tier->duration}}</td>
            <td>

              <input type="checkbox" name="tier_id" value="{{$tier->id}}"/>
            </td>
          </tr>
        @endforeach

      </tbody>
    </table>
  </div>


  <section>
    <div class="bt-drop-in-wrapper">
        <div id="bt-dropin"></div>
    </div>

    <input id="nonce" name="payment_method_nonce" type="hidden" />
    <button class="button" type="submit"><span>Test Transaction</span></button>
  </section>


</form>



{{-- BRAINTREE PAYMENT --}}
  <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>

  <script>
    var form = document.querySelector('#payment-form');
    //Se occorre registrare i metodi di pagamento, bisogna creare customer_id (Id braintree) su tabella user
    var client_token = "sandbox_8hrkkqn6_dh357c6zpkqxdvm9"; // PER TEST, TOKEN STATICO ACCOUNT BRAINTREE
    //TODO nascondere nel back end

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
        instance.requestPaymentMethod(function (err, payload) {
          if (err) {
            console.log('Request Payment Method Error', err);
            return;
          }
          // Add the nonce to the form and submit
          document.querySelector('#nonce').value = payload.nonce;

          form.submit();
        });
      });
    });
</script>
