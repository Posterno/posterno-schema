<template>
	<div id="app">

		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper metabox-holder">

			<wp-row :gutter="20" class="postbox-container">
				<wp-col :span="10">
					<div class="postbox generate-metabox start-customizing">
						<div class="inside">
							<div class="non-action">
								<h1>{{labels.structured.step1_title}}</h1>

								<div class="excerpt">
									<p>{{labels.structured.step1_description}}</p>
								</div>

								<form action="#">
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row">{{labels.settings.where.label}}</th>
												<td>
													<fieldset>
														<p>
															<label><input name="schema_position" v-model="newSchemaMode" type="radio" value="global" :disabled="loading" >{{labels.settings.where.global}}</label><br>
															<label><input name="schema_position" v-model="newSchemaMode" type="radio" value="type" :disabled="loading" >{{labels.settings.where.type}}</label>
														</p>
													</fieldset>
												</td>
											</tr>
											<tr>
												<th scope="row">{{labels.settings.schemas.label}}</th>
												<td>
													<fieldset>
														<Select2 v-model="newSchemaName" :options="availableSchemas" :disabled="loading" :settings="{ width: '100%', placeholder: labels.settings.schemas.label }"/>
													</fieldset>
												</td>
											</tr>
											<tr v-if="isListingTypeRequired()">
												<th scope="row">{{labels.settings.listing_types.label}}</th>
												<td>
													<fieldset>
														<Select2 v-model="newSchemaListingType" :options="availableListingTypes" :disabled="loading" :settings="{ width: '100%', placeholder: labels.settings.listing_types.label, multiple: true }"/>
													</fieldset>
												</td>
											</tr>
										</tbody>
									</table>

									<wp-notice type="warning" alternative v-if="showNoTypesMessage()">{{labels.settings.listing_types.not_found}}</wp-notice>

								</form>

								<wp-notice type="error" alternative v-if="isError">{{statusMessage}}</wp-notice>

							</div>

							<div id="major-publishing-actions">
								<div id="publishing-action">
									<wp-spinner v-if="loading"></wp-spinner>
									<wp-button type="primary" :disabled="! canSubmit()" @click="submit()">{{labels.add}} →</wp-button>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</wp-col>
				<wp-col :span="5">

					<div class="helper-list">
						<router-link to="/" class="back-btn">{{labels.back}}</router-link>
						<ul>
							<li v-for="(item, index) in labels.structured.step1_lists" :key="index">
								<span v-html="item.text"></span> <a :href="item.url" v-if="item.url">{{labels.readmore}} →</a>
							</li>
						</ul>
					</div>

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
	name: 'add-listings-schema',
	components: {
		AdminHeader
	},
	mounted() {

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
			newSchemaMode: 'global',
			newSchemaName: '',
			newSchemaListingType: '',
			availableSchemas: [],
			availableListingTypes: [],
			loading: false,
			isError: false,
			statusMessage: '',
		}
	},
	methods: {

		/**
		 * Verify if the listing type field is required.
		*/
		isListingTypeRequired() {

			let required = false;

			if ( this.newSchemaMode === 'type' ) {
				required = true
			} else {
				required = false
			}

			return required;

		},

		/**
		 * Determine if the form can be submitted.
		*/
		canSubmit() {

			let canSubmit = false

			if ( this.newSchemaMode === 'global' && this.newSchemaName ) {
				canSubmit = true
			} else if ( this.newSchemaMode === 'type' && this.availableListingTypes.length > 0 && this.newSchemaListingType ) {
				canSubmit = true
			}

			if ( this.loading === true ) {
				canSubmit = false
			}

			return canSubmit
		},

		/**
		 * Show warning message if no available listing types and user has selected the "type" mode.
		*/
		showNoTypesMessage() {

			let show = false

			if ( this.newSchemaMode === 'type' && ! this.availableListingTypes.length > 0 ) {
				show = true
			}

			return show

		},

		/**
		 * Show an error message.
		*/
		showError( message = false ) {

			this.loading = false
			this.isError = true
			this.statusMessage = message

		},

		/**
		 * Submit the form.
		 */
		submit() {

			this.loading = true

			axios.post( pno_schema_editor.ajax,
				qs.stringify({
					nonce: pno_schema_editor.nonce,
					mode: this.newSchemaMode,
					schema: this.newSchemaName,
					types: this.newSchemaListingType,
				}),
				{
					params: {
						action: 'pno_create_listing_schema',
					},
				}
			)
			.then( response => {

				this.$router.push({ name: 'edit', params: { id: response.data.data } })

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

<style lang="scss">
#app {
	.wrapper {
		margin:30px 20px;
	}

	.postbox-container {
		float: none;

		.inside {
			padding-bottom: 0;
			margin-bottom: 0;
		}

		.postbox .non-action {
			padding: 40px;
		}

		#major-publishing-actions {
			margin: 0 -12px;

			.vue-wp-spinner {
				float: none;
				margin-right: 10px;
			}
		}

		h1 {
			margin: 0;
			padding: 0;
			font-size: 28px;
			line-height: 38px;
			color: #222;
			font-weight: 400;
		}

		.excerpt {
			font-size: 20px;
			line-height: 30px;
			margin: 10px 0 0;
			color: #777777;

			p {
				font-size: 20px;
				line-height: 30px;
			}
		}

		select {
			width: 100%;
		}

	}

	.select2 {
		li {
			margin-bottom: 0;
		}
	}

	.select2-container--default .select2-search--inline .select2-search__field {
		width: 100% !important;
	}

	.helper-list {

		.back-btn {
			display: block;
			text-align: right;
			margin-bottom: 30px;
		}

		ul {
			margin: 0;
			padding: 0;
			color: #797979;
			li {
				padding: 0 0 8px;
				margin: 0 0 20px;
				border-bottom: 1px solid #ddd;
				opacity: 0;
				transform: translateX(10px);
				transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
				animation: FadeIn 0.2s linear;
  				animation-fill-mode: both;
				@for $i from 1 through 10 {
					&:nth-child(#{$i}n) {
						animation-delay: #{$i * 0.3}s;
					}
				}

				a {
					display: block;
					margin-top: 5px;
				}
			}
		}
	}

}

@keyframes FadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
	transform: translateX(0px);
  }
}

</style>
