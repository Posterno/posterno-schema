<?php

namespace PNO\SchemaOrg\Generator;

use PNO\SchemaOrg\Generator\Writer\Filesystem;
use PNO\SchemaOrg\Generator\Parser\DefinitionParser;

class PackageGenerator {

	public function generate( Definitions $definitions ) {
		$types = ( new DefinitionParser() )->parse( $definitions );

		$filesystem = new Filesystem( __DIR__ . '/..' );

		$filesystem->clear();

		$filesystem->createTypesList( $types );

		$filesystem->cloneStaticFiles();

		$types->each(
			function ( Type $type ) use ( $filesystem ) {
				$filesystem->createType( $type );
			}
		);

		$filesystem->createBuilderClass( $types );

	}
}
