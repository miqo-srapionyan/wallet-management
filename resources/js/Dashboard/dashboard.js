import Vue from "vue";
import store from "./store/store";
import Dashboard from './Main'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import { Bootstrap } from 'bootstrap'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';


Vue.use(Vuetify);
Vue.use(ElementUI);

Vue.filter('numberFormat', function (n) {
    if(n == null) return 0;
    return n.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
});

const dashboard = new Vue({
    el: '#dashboard',
    components:{
        'dashboard': Dashboard,
    },
    store: store,
    vuetify: new Vuetify()
});



