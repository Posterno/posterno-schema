<template>
	<div id="app">
		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		edit here
	</div>
</template>

<script>
import AdminHeader from '../components/pno-admin-header'

export default {
	name: 'edit-listing-schema',
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

<style lang="scss" scoped>
#app {
	.wrapper {
		margin:30px 20px;
	}
}
</style>
