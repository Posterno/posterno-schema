<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper">

			<wp-notice type="error" v-if="isError" dismissible>{{statusMessage}}</wp-notice>

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
				<tbody>
					<tr v-for="(schema, key) in schemas" :key="key">
						<td class="column-primary" :data-colname="labels.table.name">
							<router-link :to="{ name: 'edit', params: {id: schema.id } }">{{schema.name}}</router-link>
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
							<wp-button>{{labels.table.delete}}</wp-button>
						</td>
					</tr>
					<tr class="no-items" v-if="schemas < 1 && ! loading">
						<td class="colspanchange" colspan="4">
							<strong>{{labels.table.not_found}}</strong>
						</td>
					</tr>
					<tr class="no-items" v-if="loading">
						<td class="colspanchange" colspan="4">
							<wp-spinner></wp-spinner>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
</template>

<script>
import axios from 'axios'
import AdminHeader from '../components/pno-admin-header'

export default {
	name: 'listings-schema-editor',
	components: {
		AdminHeader
	},
	mounted() {

		this.loadSchemas()

	},
	data() {
		return {
			logo_url: pno_schema_editor.plugin_url + '/assets/imgs/logo.svg',
			labels: pno_schema_editor.labels,
			adminLinks: [
				{
					title: pno_schema_editor.labels.documentation,
					url: 'https://docs.posterno.com/'
				}
			],
			loading: false,
			schemas: [],
			isError: false,
			statusMessage: '',
		}
	},
	methods: {

		/**
		 * Show an error message.
		*/
		showError( message = false ) {

			this.loading = false
			this.isError = true
			this.statusMessage = message

		},

		/**
		 * Load schemas from the database.
		*/
		loadSchemas() {

			this.loading = true

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
