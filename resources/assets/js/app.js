
require('./bootstrap')

var bootstrap = require('bootstrap')


window.Vue = require('vue')
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

// == VueX
import Vuex from 'vuex'
import state from "./store/state"
import mutations from "./store/mutations"
import actions from "./store/actions"

const store = new Vuex.Store({
    state,
    getters: {},
    mutations,
    actions
});
window.store = store


import VModal from 'vue-js-modal/dist/index.nocss'
Vue.use(VModal)


//other packages
require('jquery-ui/ui/widget')
require("jquery-ui/ui/widgets/slider")
require('jquery-ui-touch-punch')
require('jquery-validation')
require('jquery-form')

import Cookies from 'js-cookie'

import toastr from 'toastr'
window.toastr = toastr

import bootbox from 'bootbox'
window.bootbox = bootbox

import intlTelInput from 'intl-tel-input'
window.intlTelInput = intlTelInput

import Shuffle from 'shufflejs'
window.Shuffle = Shuffle

//vanilla & JQ
document.addEventListener("DOMContentLoaded", function() {
  require('./libs/blog')
})

require('./vanilla/scroll')
require('./vanilla/jquery.steps')

require('./libs/modals')
require('./libs/main')
require('./libs/add_order_pf')
require('./libs/common')



//Vue
// Vue.component('soc-select', require('./components/SocialSelectComponent.vue').default)
