<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrap links-wrap">
			<router-link to="/" class="back-btn page-title-action">{{labels.back}}</router-link> <router-link to="/add" class="back-btn page-title-action">{{labels.add}}</router-link>
		</div>

		<div class="wrapper" id="poststuff">

			<wp-row :gutter="20" class="postbox-container">

				<wp-col :span="24">
					<wp-notice type="success" dismissible v-if="isSuccess">{{statusMessage}}</wp-notice>
					<wp-notice type="error" dismissible v-if="isError">{{statusMessage}}</wp-notice>
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" v-model="schema.title" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
						</div>
					</div>
				</wp-col>

				<wp-col :sm="24" :lg="19">

					<wp-tabs class="pno-tabs">

						<wp-tab-item :label="labels.schema_edit.props_tab">

							<wp-metabox :title="labels.schema_edit.title">
								<wp-spinner class="properties-spinner" v-if="schemaLoading"></wp-spinner>
								<jsoneditor v-if="canPerformAction() || saving" ref="editor" :json="schema.json" :options="propertiesOptions" />
							</wp-metabox>

						</wp-tab-item>

						<wp-tab-item :label="labels.schema_edit.fields">

							<wp-metabox :title="labels.schema_edit.fields">

								<div v-for="(field, key) in availableFields" :key="key">
									<p>{{field.name}} - <code>%_{{field.meta}}_%</code></p>
								</div>

							</wp-metabox>

						</wp-tab-item>

					</wp-tabs>

				</wp-col>

				<wp-col :sm="24" :lg="5">

					<wp-metabox :title="labels.schema_edit.title_edit">

						<form action="#">

							<fieldset class="cf-container">
								<div class="cf-container__fields">

									<div class="cf-field cf-radio">
										<div class="cf-field__head">
											<label class="cf-field__label">{{labels.settings.where.label}}</label>
										</div>

										<div class="cf-field__body">
											<ul class="cf-radio__list">
												<li class="cf-radio__list-item">
													<input name="schema_position" id="global" v-model="schema.mode" type="radio" value="global" class="cf-radio__input" :disabled="! canPerformAction()" >
													<label class="cf-radio__label" for="global">{{labels.settings.where.global}}</label>
												</li>
												<li class="cf-radio__list-item">
													<input name="schema_position" id="type" v-model="schema.mode" type="radio" value="type" class="cf-radio__input" :disabled="! canPerformAction()" >
													<label class="cf-radio__label" for="type">{{labels.settings.where.type}}</label>
												</li>
											</ul>
										</div>
									</div>

									<div class="cf-field cf-select" v-if="isListingTypeRequired()">
										<div class="cf-field__head">
											<label class="cf-field__label">{{labels.settings.listing_types.label}}</label>
										</div>
										<div class="cf-field__body">
											<Select2 class="cf-select__input" v-model="schema.listing_types" :options="availableListingTypes" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.settings.listing_types.label, multiple: true }"/>
										</div>
									</div>

								</div>
							</fieldset>

						</form>

						<template v-slot:metabox-footer>
							<div id="major-publishing-actions">
								<div id="delete-action">
									<popper
										trigger="click"
										:append-to-body="true"
										:options="{
										placement: 'left',
										modifiers: { offset: { offset: '0,10px' } }
										}"
										@show="toggleDeleteBtn($event)"
										@hide="toggleDeleteBtn($event)"
										>
										<div class="popper schema-delete">
											<p>{{labels.schema_edit.confirm_delete}} <wp-button type="primary" @click="deleteSchema()">{{labels.table.delete}}</wp-button></p>
										</div>

										<wp-button :disabled="! canPerformAction() || ! canDelete" slot="reference">{{labels.table.delete}}</wp-button>
									</popper>
								</div>
								<div id="publishing-action">
									<wp-spinner v-if="saving"></wp-spinner>
									<wp-button type="primary" :disabled="! canPerformAction()" @click="saveSchema()">{{labels.table.save}}</wp-button>
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
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css'
import jsoneditor from '../components/jsoneditor'
import Ajv from 'ajv'

export default {
	name: 'edit-listing-schema',
	components: {
		AdminHeader,
		'popper': Popper,
		jsoneditor
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
				title: '',
				listing_types: [],
				url: '',
				json: '',
			},

			propertiesOptions: {
				search: false,
				colorPicker: false,
				enableSort: false,
				enableTransform: false,
				mode: 'tree',
    			modes: ['code', 'tree'],
				ajv: Ajv({ allErrors: false, format: 'full', unknownFormats: 'ignore', verbose: true, logger: false })
			},

			isError: false,
			isSuccess: false,
			statusMessage: '',
			schemaLoading: false,
			saving: false,
			availableListingTypes: [],
			availableFields: [],
			canDelete: true,
			deleting: false,

		}
	},
	methods: {

		/**
		 * Display an error message within the app.
		 */
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

		/**
		 * Load schema details from the database.
		*/
		loadSchemaDetails() {

			this.schemaLoading = true

			const configParams = {
				nonce: pno_schema_editor.editSchemaNonce,
				action: 'pno_get_listing_schema',
				schema: this.schemaID,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.schemaLoading = false

				if ( response.data.success === true ) {

					this.schema = {
						name: response.data.data.name,
						mode: response.data.data.mode,
						listing_types: response.data.data.listing_types,
						title: response.data.data.title,
						url: response.data.data.schema_url,
						json: JSON.parse(response.data.data.json)
					}

					Object.keys( response.data.data.fields ).forEach( key => {
						this.availableFields.push( {
							id: key,
							name: response.data.data.fields[key].name,
							meta: response.data.data.fields[key].meta,
						} )
					});

				}

			})
			.catch( error => {

				this.schemaLoading = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}
			})

		},

		/**
		 * Determine if actions can be performed or not.
		 */
		canPerformAction() {

			let pass = true

			if ( this.propertiesLoading === true ) {
				pass = false
			} else if ( this.saving === true ) {
				pass = false
			} else if ( this.schemaLoading === true ) {
				pass = false
			}

			return pass

		},

		/**
		 * Show success messsage.
		 */
		showSuccess( message ) {
			this.isError = false
			this.isSuccess = true
			this.statusMessage = message
		},

		/**
		 * Save schema settings in the database.
		 */
		saveSchema() {

			this.saving = true

			const propertiesEditor = this.$refs.editor.editor
			const json = propertiesEditor.get()

			axios.post( pno_schema_editor.ajax,
				qs.stringify({
					nonce: pno_schema_editor.saveListingSchemaNonce,
					post_id: this.schemaID,
					schema: this.schema,
					json: JSON.stringify( json )
				}),
				{
					params: {
						action: 'pno_save_listing_schema',
					},
				}
			)
			.then( response => {

				this.saving = false

				if ( response.data.success === true ) {
					this.showSuccess( this.labels.schema_edit.saved )
				}

			})
			.catch( error => {

				this.saving = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
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
		deleteSchema() {

			this.schemaLoading = true
			this.propertiesLoading = true

			axios.post( pno_schema_editor.ajax,
				qs.stringify({
					nonce: pno_schema_editor.deleteSchemaNonce,
					schema: this.schemaID,
				}),
				{
					params: {
						action: 'pno_delete_schema',
					},
				}
			)
			.then( response => {

				this.$router.push({ name: 'home', query: { deleted: true } })

			})
			.catch( error => {

				this.schemaLoading = false
				this.propertiesLoading = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}
			})

		},

	}
}
</script>

<style lang="scss" scoped>
#app {
	.wrapper {
		margin:10px 0px 10px 20px;
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
		&.first-row {
			border-top: 0;
		}
	}

	.schema-settings {
		label {
			margin-bottom:10px;
		}
	}

	#titlediv {
		margin-bottom: 10px;
	}

	.wrap.links-wrap {
		margin: 30px 20px;
	}

	.carbon-separator {
		background: #f5f5f5;
		h3 {
			font-size: 16px !important;
			font-weight: 600;

			a, span {
				font-size: 14px;
			}
		}
	}

	.prop-message {
		margin-top: 20px;
	}

	.structure {
		.button {
			margin-top: 5px;
		}
		.import-btn {
			float:right;
		}
	}

}

.cf-container .cf-field {
	border:0;
	&.cf-radio,
	&.cf-select {
		border: 0;
		padding: 10px 0 20px;
	}
}

</style>
