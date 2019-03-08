import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/listings-schema-home.vue'
import Add from './views/listings-schema-add.vue'
import Edit from './views/listings-schema-edit.vue'

Vue.use(Router)

export default new Router({
	routes: [{
			path: '/',
			name: 'home',
			component: Home
		},
		{
			path: '/add',
			name: 'add',
			component: Add
		},
		{
			path: '/edit/:id',
			name: 'edit',
			component: Edit
		},
	]
})
