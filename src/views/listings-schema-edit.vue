<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper" id="poststuff">

			<h1>{{labels.schema_edit.title_edit}}: {{schema.name}}</h1>
			<router-link to="/" class="back-btn">{{labels.back}}</router-link>

			<wp-row :gutter="20" class="postbox-container">

				<wp-col :span="24">
					<wp-notice type="success" dismissible v-if="isSuccess">{{statusMessage}}</wp-notice>
					<wp-notice type="error" dismissible v-if="isError">{{statusMessage}}</wp-notice>
				</wp-col>

				<wp-col :span="19">

					<wp-metabox :title="labels.schema_edit.title">

						<wp-spinner class="properties-spinner" v-if="propertiesLoading"></wp-spinner>

					</wp-metabox>

				</wp-col>

				<wp-col :span="5">

					<wp-metabox :title="labels.schema_edit.title_edit">

						<form action="#">


							<fieldset class="container-holder carbon-grid carbon-fields-collection">
								<div class="carbon-container carbon-container-post_meta">

									<div class="carbon-field carbon-radio">
										<label>{{labels.settings.where.label}}</label>
										<div class="field-holder">
											<div class="carbon-field-group-holder">
												<label><input name="schema_position" v-model="schema.mode" type="radio" value="global" :disabled="! canPerformAction()" >{{labels.settings.where.global}}</label>
												<label><input name="schema_position" v-model="schema.mode" type="radio" value="type" :disabled="! canPerformAction()" >{{labels.settings.where.type}}</label>
											</div>
										</div>
									</div>

									<div class="carbon-field carbon-select">
										<label>{{labels.settings.schemas.label}}</label>
										<div class="field-holder">
											<div class="carbon-field-group-holder">
												<Select2 v-model="schema.name" :options="availableSchemas" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.settings.schemas.label }"/>
											</div>
										</div>
									</div>

									<div class="carbon-field carbon-select" v-if="isListingTypeRequired()">
										<label>{{labels.settings.listing_types.label}}</label>
										<div class="field-holder">
											<div class="carbon-field-group-holder">
												<Select2 v-model="schema.listing_types" :options="availableListingTypes" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.settings.listing_types.label, multiple: true }"/>
											</div>
										</div>
									</div>

								</div>
							</fieldset>

						</form>

						<template v-slot:metabox-footer>
							<div id="major-publishing-actions">
								<div id="delete-action">
									<wp-button :disabled="! canPerformAction()">{{labels.table.delete}}</wp-button>
								</div>
								<div id="publishing-action">
									<wp-button type="primary" :disabled="! canPerformAction()">{{labels.table.save}}</wp-button>
								</div>
								<div class="clear"></div>
							</div>
						</template>
					</wp-metabox>

				</wp-col>
			</wp-row>

		</div>

	</div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'
import AdminHeader from '../components/pno-admin-header'

export default {
	name: 'edit-listing-schema',
	components: {
		AdminHeader
	},
	mounted() {

		this.schemaID = this.$route.params.id
		this.loadSchemaDetails()

		const vm = this

		this.availableSchemas = pno_schema_editor.schema

		Object.keys( pno_schema_editor.listing_types ).forEach( function( key ) {
			vm.availableListingTypes.push( { id: key, text: pno_schema_editor.listing_types[ key ] } )
		});

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
			schemaID: '',
			schema: {
				name: '',
				mode: '',
				listing_types: [],
			},
			isError: false,
			isSuccess: false,
			statusMessage: '',
			propertiesLoading: false,
			availableSchemas: [],
			availableListingTypes: [],
		}
	},
	methods: {

		showError( message = false ) {

			this.isError = true
			this.isSuccess = false
			this.statusMessage = message

		},

		/**
		 * Verify if the listing type field is required.
		*/
		isListingTypeRequired() {

			let required = false;

			if ( this.schema.mode === 'type' ) {
				required = true
			} else {
				required = false
			}

			return required;

		},

		loadSchemaDetails() {

			this.propertiesLoading = true

			const configParams = {
				nonce: pno_schema_editor.editSchemaNonce,
				action: 'pno_get_listing_schema',
				schema: this.schemaID,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				if ( response.data.success === true ) {

					this.schema = {
						name: response.data.data.name,
						mode: response.data.data.mode,
						listing_types: response.data.data.listing_types
					}

				}

			})
			.catch( error => {

				this.propertiesLoading = false

				if ( error.response.data ) {
					this.showError( error.response.data )
				} else {
					this.showError( error.message )
				}
			})

		},

		canPerformAction() {

			let pass = true

			if ( this.propertiesLoading === true ) {
				pass = false
			}

			return pass

		}

	}
}
</script>

<style lang="scss" scoped>
#app {
	.wrapper {
		margin:10px 20px;
	}
	.postbox-container {
		margin-top: 30px;
	}

	.vue-wp-notice {
		margin-bottom: 20px;
	}

	.properties-spinner {
		margin: 10px 0;
	}

	.carbon-fields-collection {
		padding-bottom:0px;
		margin: 0 -12px;
	}

	.carbon-field-group-holder {
		display: block;
		label {
			font-weight: normal;
		}
	}

	.carbon-field {
		border-right:none;
		&:first-child {
			border-top: 0;
		}
	}

}
</style>
