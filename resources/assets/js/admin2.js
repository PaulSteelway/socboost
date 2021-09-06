

const moment = require('moment')

Vue.use(require('vue-moment'),{
    moment
});

import 'vue2-datepicker/index.css'


// window.Vue = require('vue')
//
//
// import VueAxios from 'vue-axios'
// Vue.use(VueAxios, axios)






//Vue
Vue.component('date', require('./components/helpers/DatePicker.vue').default)
Vue.component('recalculate-component', require('./components/RecalculateTransactionComponent.vue').default)


// const app = new Vue({
//   el: '#app',
// });
