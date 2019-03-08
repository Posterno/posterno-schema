<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper" id="poststuff">

			<h1>{{labels.schema_edit.title_edit}}: </h1>
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
import AdminHeader from '../components/pno-admin-header'

export default {
	name: 'edit-listing-schema',
	components: {
		AdminHeader
	},
	mounted() {

		this.schemaID = this.$route.params.id
		this.loadSchemaDetails()

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
			isError: false,
			isSuccess: false,
			statusMessage: '',

			propertiesLoading: false,

		}
	},
	methods: {

		loadSchemaDetails() {

			this.propertiesLoading = true

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

}
</style>
