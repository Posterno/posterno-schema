import Vue from 'vue'
import App from './listings-schema-editor.vue'
import VueWP from '@alessandrotesoro/vuewp'
import router from './listings-schema-router'
import Select2 from 'v-select2-component'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

Vue.component('Select2', Select2);
Vue.component('Treeselect', Treeselect);

Vue.use(VueWP)

Vue.config.productionTip = false

new Vue({
	router,
	render: h => h(App),
}).$mount('#pno-listings-schema')
