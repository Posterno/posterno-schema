<template>
	<div id="app">

		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

	</div>
</template>

<script>
import AdminHeader from './components/pno-admin-header'

export default {
	name: 'listings-schema-editor',
	components: {
		AdminHeader
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
		}
	}
}
</script>

<style>
</style>
