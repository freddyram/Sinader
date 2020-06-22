
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue              from 'vue'
import Vuetify          from 'vuetify'
import Vuex             from 'vuex'
import VueRouter        from 'vue-router'
import store            from './store';
import router           from './routes'
import VueApexCharts    from 'vue-apexcharts'

import AppComponent         from './components/AppComponent'

Vue.use(Vuex);
Vue.use(VueApexCharts)

window.axios = require('axios');
axios.defaults.headers.common = {
    // 'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
};

Vue.prototype.$http = window.axios

Vue.use(Vuetify, {
    theme: {
        main_green: '#242351',
        secondary_green: '#45449E',
        highlight_green: '#446E9E',
        side_bar_gray: '#242351',

        seconday_gray: '#EEEEEE',

        ds_138: '#079992',
        readings: '#4388A5',
        covs: '#00596D',
        
        primary: '#1976D2',
        secondary: '#424242',
        accent: '#82B1FF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107'
        


        // main_green: '#079992',
        // secondary_green: '#38ACA9',
        // highlight_green: '#6BEC87',
        // side_bar_gray: '#595959',

        // seconday_gray: '#EEEEEE',

        // ds_138: '#079992',
        // readings: '#4388A5',
        // covs: '#00596D',
        
        // primary: '#1976D2',
        // secondary: '#424242',
        // accent: '#82B1FF',
        // error: '#FF5252',
        // info: '#2196F3',
        // success: '#4CAF50',
        // warning: '#FFC107'

    }
});


const app = new Vue({
    el: '#App',
    render: h => h(AppComponent),
    store,
    router: router,
    created () {

        let token_vu = document.head.querySelector('meta[name="token"]');
       

        if (token_vu.content){  
            window.axios.defaults.headers.common['Authorization'] = 'Bearer '+token_vu.content;
            this.$store.commit('changeToken',token_vu.content)
        }else{
            window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.getters.token;
        }


        window.addEventListener('beforeunload', this.handler)
    },

});
