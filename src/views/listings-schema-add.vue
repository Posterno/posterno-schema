<template>
	<div id="app">

		<AdminHeader :links="adminLinks">
			{{labels.listing.title}}
		</AdminHeader>

		<div class="wrapper">
			<section class="l_admin-area-wrapper">
				<div class="l_admin-onboard-wrapper">
					<div class="l_admin-onboard-controllers">
						<router-link to="/" class="button btn-link l_admin-pull-right">{{labels.back}}</router-link>
					</div>
					<div class="l_admin-onboard-slider l_admin-row">
						<div class="l_admin-onboard-slide l_admin-animate l_admin-onboard-slide-current">
							<div class="l_admin-column l_admin-span-8 postbox">
								<div class="l_admin-content-large">
									<div class="l_admin-section-title l_admin-no-push-bottom">
										<h3 class="l_admin-heading">
										</h3>
										<div class="l_admin-excerpt">
										</div>
									</div>
								</div>
								<div class="l_admin-button-well">
									<a class="l_admin-button button-primary l_admin-pull-right onboard-next-step" href="">{{labels.setup}} →</a>
								</div>
							</div>
							<div class="l_admin-column l_admin-span-4 no-gutter">
								<div class="l_admin-content">
									<ul class="l_admin-help-list">
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
import AdminHeader from '../components/pno-admin-header'

export default {
	name: 'add-listings-schema',
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
