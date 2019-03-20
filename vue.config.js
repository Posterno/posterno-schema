module.exports = {
	filenameHashing: false,
	productionSourceMap: false,
	// see https://github.com/vuejs/vue-cli/blob/dev/docs/webpack.md
	chainWebpack: config => {
		// If you wish to remove the standard entry point
		config.entryPoints.delete('app')
		// then add your own
		config
			.entry('listings-schema-editor')
			.add('./src/listings-schema-editor.js')
			.end()
	}
}
