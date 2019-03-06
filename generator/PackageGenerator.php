<?php

namespace Posterno\SchemaOrg\Generator;

use Posterno\SchemaOrg\Generator\Writer\Filesystem;
use Posterno\SchemaOrg\Generator\Parser\DefinitionParser;

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
