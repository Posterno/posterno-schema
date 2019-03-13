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

					<wp-metabox :title="labels.schema_edit.title">

						<wp-spinner class="properties-spinner" v-if="schemaLoading"></wp-spinner>

						<fieldset class="container-holder carbon-grid carbon-fields-collection schema-settings" v-if="canPerformAction() || saving">
							<div class="carbon-container carbon-container-post_meta">
								<div class="carbon-field has-width first-row" style="flex-basis: 33%;">
									<div class="field-holder">
										<label>
											{{labels.schema_edit.primary_label}}
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="schema.primarySchemaChildren" :options="primarySchemaChildren" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.additional_type, multiple: false }"/>
										</div>
									</div>
								</div>
								<div class="carbon-field has-width first-row" style="flex-basis: 33%;" v-if="secondarySchemaChildren.length > 0">
									<div class="field-holder">
										<label>
											{{labels.schema_edit.secondary_label}}
										</label>
										<div class="carbon-field-group-holder">
											<Select2 v-model="schema.secondarySchemaChildren" :options="secondarySchemaChildren" :disabled="! canPerformAction()" :settings="{ width: '100%', placeholder: labels.schema_edit.additional_type, multiple: false }" />
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
										<h3>{{this.schema.name}} - {{labels.schema_edit.properties}} - <a :href="schema.url" target="_blank">{{labels.schema_edit.schema_url}}</a></h3>
									</div>
								</div>

								<wp-spinner class="properties-spinner" v-if="propertiesLoading"></wp-spinner>

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

				<wp-col :sm="24" :lg="5">

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
									<popper
										trigger="click"
										:append-to-body="true"
										:options="{
										placement: 'left',
										modifiers: { offset: { offset: '0,10px' } }
										}">
										<div class="popper schema-delete">
											<p>{{labels.schema_edit.confirm_delete}} <wp-button type="primary">{{labels.table.delete}}</wp-button></p>
										</div>

										<wp-button :disabled="! canPerformAction()" slot="reference">{{labels.table.delete}}</wp-button>
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
import 'vue-popperjs/dist/vue-popper.css';

export default {
	name: 'edit-listing-schema',
	components: {
		AdminHeader,
		'popper': Popper,
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
				url: '',
			},
			isError: false,
			isSuccess: false,
			statusMessage: '',
			propertiesLoading: false,
			schemaLoading: false,
			saving: false,
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
						primarySchemaChildren: response.data.data.primary_schema,
						secondarySchemaChildren: response.data.data.secondary_schema,
						tertiarySchemaChildren: response.data.data.tertiary_schema,
					}

					this.loadPrimarySchemaChildren()
					this.loadSchemaPropSettings( response.data.data.properties )

				}

			})
			.catch( error => {

				this.schemaLoading = false

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
			} else if ( this.saving === true ) {
				pass = false
			} else if ( this.schemaLoading === true ) {
				pass = false
			}

			return pass

		},

		/**
		 * Load first level child schemas.
		*/
		loadPrimarySchemaChildren() {

			this.schemaLoading = true
			this.primarySchemaChildren = []
			this.secondarySchemaChildren = []
			this.tertiarySchemaChildren = []

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: this.schema.name,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.schemaLoading = false

				Object.keys( response.data.data.childs ).forEach( key => {
					this.primarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
				});

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
		 * Load child schemas of the primary schema.
		*/
		loadSecondaryChildren( selectedSchema ) {

			this.schemaLoading = true
			this.secondarySchemaChildren = []
			const vm = this

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: selectedSchema,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.schemaLoading = false

				if ( typeof(response.data.data.childs) !== 'undefined' && response.data.data.childs !== null ) {
					Object.keys( response.data.data.childs ).forEach( key => {
						this.secondarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
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
		 * Load child schemas of the secondary schema.
		 */
		loadTertiaryChildren( selectedSchema ) {

			this.schemaLoading = true
			this.tertiarySchemaChildren = []

			const configParams = {
				nonce: pno_schema_editor.childSchemaNonce,
				action: 'pno_get_child_schema',
				schema: selectedSchema,
			}

			axios.get( pno_schema_editor.ajax, {
				params: configParams
			})
			.then( response => {

				this.schemaLoading = false

				if ( typeof(response.data.data.childs) !== 'undefined' && response.data.data.childs !== null ) {
					Object.keys( response.data.data.childs ).forEach( key => {
						this.tertiarySchemaChildren.push( { id: response.data.data.childs[ key ].label, text: response.data.data.childs[ key ].label } )
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

					let mainChilds = []

					let cfBlock = {
						id: 0,
						text: this.labels.schema_edit.cf,
						children: mainChilds
					}

					Object.keys( response.data.data.fields ).forEach( key => {
						mainChilds.push( { id: key, text: response.data.data.fields[ key ].name } )
					});

					this.availableListingFields.push( cfBlock )

				}

				if ( typeof(response.data.data.meta) !== 'undefined' && response.data.data.meta !== null ) {
					Object.keys( response.data.data.meta ).forEach( key => {
						let childs = []
						Object.keys( response.data.data.meta[ key ].settings ).forEach( settingkey => {
							childs.push({
								id: settingkey,
								text: response.data.data.meta[ key ].settings[ settingkey ],
							})
						})
						let metaBlock = {
							id: key,
							text: response.data.data.meta[ key ].label,
							children: childs
						}
						this.availableListingFields.push( metaBlock )
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
		loadSchemaPropSettings( dbvalues = false ) {

			this.propertiesLoading = true
			this.properties = []

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

				if ( dbvalues ) {
					Object.keys( dbvalues ).forEach( key => {

						const propName = key
						const propValue = dbvalues[key]

						if ( typeof( this.properties[propName] ) !== 'undefined' && this.properties[propName] !== null ) {
							this.properties[propName].value = propValue
						}

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
		 * Show success messsage.
		 */
		showSuccess( message ) {
			this.isError = false
			this.isSuccess = true
			this.statusMessage = message
			this.resetMessages()
		},

		/**
		 * Automatically hide the admin notice after 4 seconds.
		 */
		resetMessages() {
			let self = this
			setInterval(function() {
				self.isError = false
				self.isSuccess = false
				self.statusMessage = ''
			}, 4000)
		},

		/**
		 * Save schema settings in the database.
		 */
		saveSchema() {

			this.saving = true

			axios.post( pno_schema_editor.ajax,
				qs.stringify({
					nonce: pno_schema_editor.saveListingSchemaNonce,
					post_id: this.schemaID,
					schema: this.schema,
					properties: this.properties
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

		}

	},
	watch: {
		'schema.primarySchemaChildren': function (newVal, oldVal) {
			if ( newVal ) {
				this.loadSecondaryChildren( newVal )
			}
		},
		'schema.secondarySchemaChildren': function (newVal, oldVal) {
			if ( newVal ) {
				this.loadTertiaryChildren( newVal )
			}
		},
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

			a, span {
				font-size: 14px;
			}
		}
	}

}
</style>
