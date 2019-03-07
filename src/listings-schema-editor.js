import Vue from 'vue'
import App from './listings-schema-editor.vue'
import VueWP from '@alessandrotesoro/vuewp'
Vue.use(VueWP)

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
}).$mount('#pno-listings-schema')
