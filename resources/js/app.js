/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');
import BootstrapVue from 'bootstrap-vue' //Importing

Vue.use(BootstrapVue) // Telling Vue to use this in whole application



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 window.FilePond = require('./filepond.min.js');
 window.FilePondPluginImagePreview = require('./filepond-plugin-image-preview.min.js');

$(document).ready(init);

function init(){


  var token = $('meta[name="csrf-token"]').attr('content');
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;


  var create_apt = new Vue({
      el: '#app'
  });

  // filePondDropImg();



  var message_comp = new Vue({
      el: '#vue_messages'
  });


  // Component Pagamento
  var payment_comp = new Vue({

      el: '#vue_payment'
  });

  // Component Mappa
  var map_comp = new Vue({

      el: '#vue_map'
  });
};

// funzione per DROPIN file immagine
function filePondDropImg(){
  FilePond.registerPlugin(
      FilePondPluginImagePreview,
  );

  FilePond.setOptions({
      server: {
          url: '',
          process: {
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          }
      }
  });
  var inputElement = document.querySelector('input[type="file"]');
  var pond = FilePond.create( inputElement );
}
