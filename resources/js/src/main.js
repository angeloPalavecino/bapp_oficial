/*=========================================================================================
  File Name: main.js
  Description: main vue(js) file
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/

import 'es6-promise/auto'
import axios from 'axios'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from '../auth'
import Vue from 'vue'
import App from './App.vue'

//Funciones Globales
import FuncionesGlobales from './mixins/FuncionesGlobales';
Vue.mixin(FuncionesGlobales);

//Permisos
import Permissions from './mixins/Permissions';
Vue.mixin(Permissions);

// Vuesax Component Framework
import Vuesax from 'vuesax'

Vue.use(Vuesax)

// Theme Configurations
import '../themeConfig.js'

// Globally Registered Components
import './globalComponents.js'

// Vue Router
import router from './router'

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

// Vuex Store
import store from './store/store'

// Vuesax Admin Filters
import './filters/filters'

// VeeValidate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

//Axios
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`
Vue.use(VueAuth, auth)

// Vuejs - Vue wrapper for hammerjs
import { VueHammer } from 'vue2-hammer'
Vue.use(VueHammer)

// Google Maps
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
    load: {
        // Add your API key here
        key: 'AIzaSyCk6Qet4uRb2EaiEoBgw-eOXedRMUuoudA',
        libraries: 'places,drawing', // This is required if you use the Auto complete plug-in
        region: 'cl',
    },
})

// PrismJS
import 'prismjs'
// import 'prismjs/themes/prism-tomorrow.css'

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
