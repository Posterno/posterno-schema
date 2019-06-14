<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper">

			<wp-notice type="error" v-if="isError" dismissible>{{statusMessage}}</wp-notice>

			<wp-notice type="success" v-if="isSuccess" dismissible>{{statusMessage}}</wp-notice>

			<router-link to="/add" class="button-primary">{{labels.add}}</router-link>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th scope="col" class="column-primary">{{labels.table.name}}</th>
						<th scope="col">{{labels.table.mode}}</th>
						<th scope="col">{{labels.table.listing_types}}</th>
						<th scope="col">{{labels.table.actions}}</th>
					</tr>
				</thead>
				<tbody v-if="loading">
					<tr class="no-items">
						<td class="colspanchange" colspan="4">
							<wp-spinner></wp-spinner>
						</td>
					</tr>
				</tbody>
				<tbody v-else-if="! loading && schemas < 1">
					<tr class="no-items">
						<td class="colspanchange" colspan="4">
							<strong>{{labels.table.not_found}}</strong>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr v-for="(schema, key) in schemas" :key="key">
						<td class="column-primary" :data-colname="labels.table.name">
							<router-link :to="{ name: 'edit', params: {id: schema.id } }">{{schema.title}}</router-link>
							<button type="button" class="toggle-row"></button>
						</td>
						<td :data-colname="labels.table.mode">
							{{schema.mode}}
						</td>
						<td :data-colname="labels.table.listing_types">
							{{schema.listing_types}}
						</td>
						<td :data-colname="labels.table.actions">
							<router-link :to="{ name: 'edit', params: {id: schema.id } }" class="button">{{labels.table.edit}}</router-link>
							<popper
								trigger="click"
								:options="{
								placement: 'top',
								modifiers: { offset: { offset: '0,10px' } }
								}"
								@show="toggleDeleteBtn($event)"
								@hide="toggleDeleteBtn($event)"
								>
								<div class="popper schema-delete">
									<p>{{labels.schema_edit.confirm_delete}} <wp-button type="primary" @click="deleteSchema( schema.id )">{{labels.table.delete}}</wp-button></p>
								</div>

								<wp-button slot="reference" :disabled="! canDelete">{{labels.table.delete}}</wp-button>
							</popper>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'
import AdminHeader from '../components/pno-admin-header'
import Popper from 'vue-popperjs'
import 'vue-popperjs/dist/vue-popper.css'

export default {
	name: 'listings-schema-editor',
	components: {
		AdminHeader,
		'popper': Popper
	},
	mounted() {

		const routerQuery = this.$route.query

		if ( routerQuery.deleted ) {
			this.showSuccess( this.labels.schema_edit.deleted_message )

			this.$router.replace({
				...this.$router.currentRoute,
				query: {
					deleted: undefined,
				}
			})

		}

		this.loadSchemas()

	},
	data() {
		return {
			logo_url: pno_schema_editor.plugin_url + '/assets/imgs/logo.svg',
			labels: pno_schema_editor.labels,
			adminLinks: [
				{
					title: pno_schema_editor.labels.import,
					url: pno_schema_editor.import_url
				},
				{
					title: pno_schema_editor.labels.export,
					url: pno_schema_editor.export_url
				},
				{
					title: pno_schema_editor.labels.documentation,
					url: 'https://docs.posterno.com/'
				}
			],
			loading: false,
			schemas: [],
			isError: false,
			isSuccess: false,
			statusMessage: '',
			canDelete: true,
			deleting: false,
		}
	},
	methods: {

		/**
		 * Show an error message.
		*/
		showError( message = false ) {

			this.loading = false
			this.isError = true
			this.isSuccess = false
			this.statusMessage = message

		},

		/**
		 * Show success message.
		*/
		showSuccess( message = false ) {

			this.loading = false
			this.isError = false
			this.isSuccess = true
			this.statusMessage = message

		},

		/**
		 * Load schemas from the database.
		*/
		loadSchemas() {

			this.loading = true
			this.schemas = [];

			const configParams = {
				nonce: pno_schema_editor.getSchemasNonce,
				action: 'pno_get_listings_schemas_list'
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.loading = false

				if ( response.data.data.schemas.length > 0 ) {
					Object.keys( response.data.data.schemas ).forEach( key => {
						this.schemas.push( response.data.data.schemas[key] )
					});
				}

			})
			.catch( error => {
				if ( error.response.data ) {
					this.showError( error.response.data )
				} else {
					this.showError( error.message )
				}
			})

		},

		/**
		 * Toggle the disabled status of the delete button within the table row.
		*/
		toggleDeleteBtn( event ) {
			this.canDelete = !this.canDelete
		},

		/**
		 * Delete a schema from the database then reload the table.
		*/
		deleteSchema( schema ) {

			this.loading = true

			axios.post( pno_schema_editor.ajax,
				qs.stringify({
					nonce: pno_schema_editor.deleteSchemaNonce,
					schema: schema,
				}),
				{
					params: {
						action: 'pno_delete_schema',
					},
				}
			)
			.then( response => {

				this.toggleDeleteBtn()
				this.showSuccess( this.labels.schema_edit.deleted_message )
				this.loadSchemas()

			})
			.catch( error => {
				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}
			})

		}

	}
}
</script>

<style lang="scss" scoped>
#app {
	.wrapper {
		margin:30px 20px;

		.wp-list-table {
			margin-top: 30px;
		}

		.vue-wp-notice {
			margin-bottom: 30px;
		}

		table {
			.button {
				margin-right: 5px;
			}
			td {
				vertical-align: middle;
			}
		}
	}
}
</style>
