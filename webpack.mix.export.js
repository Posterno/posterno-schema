/**
 * Plugin Export Script
 *
 * Exports the production-ready plugin with only the files and folders needed for
 * uploading to a site or zipping. Edit the `files` or `folders` variables if
 * you need to change something.
 *
 * This configuration is a copy of the configuration from https://github.com/justintadlock/mythic/
 * modified to fit Pressmodo's plugins purposes.
 */

// Import required packages.
const mix     = require( 'laravel-mix' );
const rimraf  = require( 'rimraf' );
const fs      = require( 'fs' );

// Folder name to export the files to.
let exportPath = 'plugin';

// Plugin root-level files to include.
let files = [
	'plugin.php',
	'readme.txt'
];

// Folders to include.
let folders = [
	'app',
	'dist',
	'resources/lang',
	'resources/js',
	'resources/scss',
	'vendor'
];

// Delete the previous export to start clean.
rimraf.sync( exportPath );

// Loop through the root files and copy them over.
files.forEach( file => {

	if ( fs.existsSync( file ) ) {
		mix.copy( file, `${exportPath}/${file}` );
	}
} );

// Loop through the folders and copy them over.
folders.forEach( folder => {

	if ( fs.existsSync( folder ) ) {
		mix.copyDirectory( folder, `${exportPath}/${folder}` );
	}
} );

// Delete the `vendor/bin` and `vendor/composer/installers` folder, which can
// get left over, even in production. Mix will also create an additional
// `mix-manifest.json` file in the root, which we don't need.
mix.then( () => {

	let files = [
		'mix-manifest.json',
		`${exportPath}/vendor/bin`,
	 	`${exportPath}/vendor/composer/installers`
	];

	files.forEach( file => {
		rimraf.sync( file );
	} );
} );
