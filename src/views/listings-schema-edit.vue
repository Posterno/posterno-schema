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

				<wp-col :span="19">

					<wp-metabox :title="labels.schema_edit.title">

						<wp-spinner class="properties-spinner" v-if="propertiesLoading"></wp-spinner>

						<fieldset class="container-holder carbon-grid carbon-fields-collection schema-settings" v-if="canPerformAction()">
							<div class="carbon-container carbon-container-post_meta">
								<div class="carbon-field has-width first-row" style="flex-basis: 33%;">
									<div class="field-holder">
										<label>
											{{labels.schema_edit.primary_label}}
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="schema.primarySchemaChildren" :options="primarySchemaChildren" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.additional_type, multiple: false }" @select="loadSecondaryChildren($event)"/>
										</div>
									</div>
								</div>
								<div class="carbon-field has-width first-row" style="flex-basis: 33%;" v-if="secondarySchemaChildren.length > 0">
									<div class="field-holder">
										<label>
											{{labels.schema_edit.secondary_label}}
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="schema.secondarySchemaChildren" :options="secondarySchemaChildren" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.additional_type, multiple: false }" @select="loadTertiaryChildren($event)"/>
										</div>
									</div>
								</div>
								<div class="carbon-field has-width first-row" style="flex-basis: 33%;" v-if="tertiarySchemaChildren.length > 0">
									<div class="field-holder">
										<label>
											{{labels.schema_edit.tertiary_label}}
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="schema.tertiarySchemaChildren" :options="tertiarySchemaChildren" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.additional_type, multiple: false }" />
										</div>
									</div>
								</div>
							</div>

							<div class="carbon-container carbon-container-post_meta">
								<div class="carbon-field carbon-separator">
									<div class="field-holder">
										<h3>{{labels.schema_edit.properties}}</h3>
									</div>
								</div>
								<div class="carbon-field has-width" style="flex-basis: 33.33%;" v-for="(item, index) in properties" :key="index">
									<div class="field-holder">
										<label>
											{{item.label}}
											<span v-if="item.required" class="required">*</span>
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="item.value" :options="availableListingFields" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.field, multiple: false }" />
										</div>
									</div>
								</div>
							</div>

						</fieldset>

					</wp-metabox>

				</wp-col>

				<wp-col :span="5">

					<wp-metabox :title="labels.schema_edit.title_edit">

						<form action="#">

							<fieldset class="container-holder carbon-grid carbon-fields-collection">
								<div class="carbon-container carbon-container-post_meta">

									<div class="carbon-field carbon-radio first-row">
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
												<select name="schemaName" v-model="schema.name" :disabled="! canPerformAction()" @change="detectMainSchemaChange($event)">
													<option v-for="(schema, key) in availableSchemas" :key="key" :value="schema">{{schema}}</option>
												</select>
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
		this.loadCustomFields()

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
				primarySchemaChildren: '',
				secondarySchemaChildren: '',
				tertiarySchemaChildren: '',
			},
			isError: false,
			isSuccess: false,
			statusMessage: '',
			propertiesLoading: false,
			availableSchemas: [],
			availableListingTypes: [],
			availableListingFields: [],

			primarySchemaChildren: [],
			secondarySchemaChildren: [],
			tertiarySchemaChildren: [],

			properties: [],

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

				if ( response.data.success === true ) {

					this.schema = {
						name: response.data.data.name,
						mode: response.data.data.mode,
						listing_types: response.data.data.listing_types,
						title: response.data.data.title,
					}

					this.loadPrimarySchemaChildren()
					this.loadSchemaPropSettings()

				} else {

					this.propertiesLoading = false

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

		/**
		 * Determine if actions can be performed or not.
		 */
		canPerformAction() {

			let pass = true

			if ( this.propertiesLoading === true ) {
				pass = false
			}

			return pass

		},

		/**
		 * Load first level child schemas.
		*/
		loadPrimarySchemaChildren() {

			this.propertiesLoading = true
			this.properties = [],
			this.primarySchemaChildren = []
			this.secondarySchemaChildren = []
			this.tertiarySchemaChildren = []
			this.schema.primarySchemaChildren = null
			this.schema.secondarySchemaChildren = null
			this.schema.tertiarySchemaChildren = null

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: this.schema.name,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				Object.keys( response.data.data.childs ).forEach( key => {
					this.primarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
				});

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

		/**
		 * When the primary schema changes, reload the child schema.
		*/
		detectMainSchemaChange( event ) {
			this.loadPrimarySchemaChildren()
			this.loadSchemaPropSettings()
		},

		/**
		 * Load child schemas of the primary schema.
		*/
		loadSecondaryChildren( {id, text} ) {

			this.propertiesLoading = true
			this.secondarySchemaChildren = []
			this.tertiarySchemaChildren = []
			const vm = this

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: text,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				if ( typeof(response.data.data.childs) !== 'undefined' && response.data.data.childs !== null ) {
					Object.keys( response.data.data.childs ).forEach( key => {
						this.secondarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
					});
				}

			})
			.catch( error => {

				this.propertiesLoading = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}
			})

		},

		/**
		 * Load child schemas of the secondary schema.
		 */
		loadTertiaryChildren( {id, text} ) {

			this.propertiesLoading = true
			this.tertiarySchemaChildren = []

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: text,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				if ( typeof(response.data.data.childs) !== 'undefined' && response.data.data.childs !== null ) {
					Object.keys( response.data.data.childs ).forEach( key => {
						this.tertiarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
					});
				}
			})
			.catch( error => {

				this.propertiesLoading = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}
			})

		},

		/**
		 * Load custom listings fields.
		*/
		loadCustomFields() {

			this.propertiesLoading = true

			const configParams = {
				nonce: pno_schema_editor.listingFieldsSchemaNonce,
				action: 'pno_get_schema_listings_fields',
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				if ( typeof(response.data.data.fields) !== 'undefined' && response.data.data.fields !== null ) {
					Object.keys( response.data.data.fields ).forEach( key => {
						this.availableListingFields.push( { id: key, text: response.data.data.fields[ key ].name } )
					});
				}

			})
			.catch( error => {

				this.propertiesLoading = false

				if ( typeof(error.response) !== 'undefined' && typeof(error.response.data) !== 'undefined' ) {
					this.showError( error.response.data )
				} else if ( typeof(error.message) !== 'undefined' ) {
					this.showError( error.message )
				}

			})

		},

		/**
		 * Load setting for the selected schema.
		 */
		loadSchemaPropSettings() {

			this.propertiesLoading = true

			const configParams = {
				nonce: pno_schema_editor.propertiesSchemaNonce,
				action: 'pno_get_schema_properties',
				schema: this.schema.name,
				post_id: this.schemaID,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.propertiesLoading = false

				if ( typeof(response.data.data.props) !== 'undefined' && response.data.data.props !== null ) {
					this.properties = response.data.data.props
				}

			})
			.catch( error => {

				this.propertiesLoading = false

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
		margin:10px 20px;
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
		}
	}

}
</style>
