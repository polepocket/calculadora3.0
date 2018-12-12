import Es6Promise from 'es6-promise'
Es6Promise.polyfill() 

require('./bootstrap');
require('./enlight');
// require('es6-promise').polyfill();
window.Vue = require('vue');
 
// or import all icons if you don't care about bundle size
import 'vue-awesome/icons'
import Vue from 'vue';
import VeeValidate from 'vee-validate';
// Vue.use(VeeValidate);
/* Register component with one of 2 methods */

import Icon from 'vue-awesome/components/Icon'
Vue.component('v-icon', Icon)

Vue.component('padre-component', require('./components/PadreComponent.vue'));
Vue.component('dimension-component', require('./components/DimensionComponent.vue'));
Vue.component('personales-component', require('./components/PersonalesComponent.vue'));
Vue.component('solucion-component', require('./components/SolucionComponent.vue'));
Vue.component('map-component', require('./components/MapComponent.vue'));
Vue.component('recibo-component', require('./components/ReciboComponent.vue'));
Vue.component('agendar-component', require('./components/AgendarComponent.vue'));
Vue.component('fin-component', require('./components/FinGraciasComponent.vue'));

// or locally (in your component file)
export default {
  components: {
    'v-icon': Icon,
    'VeeValidate': VeeValidate
    // 'vueSlider': vueSlider,
    // 'Vmodal': VModal
  }
}

const app = new Vue({
    el: '#app'
});

if (window.history && history.pushState && !fin) {
  addEventListener('load', function(event) {
      history.pushState(null, null, null); // creates new history entry with same URL
      event.preventDefault();
      addEventListener('popstate', function() {
          if(final){
              location.reload();
          }else{
              Swal({
                  title: 'Aviso',
                  text: "Si sales perderás tus cambios ¿Deseas regresar?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
              }).then((result) => {
                  if (result.value) {
                      location.reload();
                  }else{
                      history.pushState(null, null, null);
                  }
              }) 
              // var stayOnPage = confirm("Usted perderá sus cambios ¿Desea regresar?");
              // if (!stayOnPage) {
              //     history.pushState(null, null, null);
              // } else {
              //     // history.back() 
              //     location.reload();
              // }    
          }
          
      });    
  });
}        