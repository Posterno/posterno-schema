import Vue from 'vue'
import App from './listings-schema-editor.vue'
import VueWP from '@alessandrotesoro/vuewp'
import router from './listings-schema-router'

Vue.use(VueWP)

Vue.config.productionTip = false

new Vue({
	router,
	render: h => h(App),
}).$mount('#pno-listings-schema')
