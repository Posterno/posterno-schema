<?php

namespace PNO\SchemaOrg\Generator\Writer;

use League\Flysystem\Adapter\Local;
use PNO\SchemaOrg\Generator\Type;
use League\Flysystem\Filesystem as Flysystem;
use PNO\SchemaOrg\Generator\TypeCollection;

class Filesystem {

	/** @var \League\Flysystem\Filesystem */
	protected $flysystem;

	/** @var \PNO\SchemaOrg\Generator\Writer\Template */
	protected $typeTemplate;

	protected $typesListTemplate;

	public function __construct( string $root ) {
		$adapter         = new Local( $root );
		$this->flysystem = new Flysystem( $adapter );

		$this->typesListTemplate    = new Template( 'types-list.php.twig' );
		$this->typeTemplate         = new Template( 'Type.php.twig' );
		$this->builderClassTemplate = new Template( 'Schema.php.twig' );
	}

	public function clear() {
		$this->flysystem->deleteDir( 'includes/generated' );
		$this->flysystem->createDir( 'includes/generated' );
	}

	public function createTypesList( TypeCollection $types ) {
		$this->flysystem->put(
			'includes/generated/types-list.php',
			$this->typesListTemplate->render( [ 'types' => $types->toArray() ] )
		);
	}

	public function cloneStaticFiles() {
		$files = $this->flysystem->listContents( 'generator/templates/static', true );

		foreach ( $files as $file ) {
			if ( $file['type'] !== 'file' ) {
				continue;
			}

			$this->flysystem->put(
				str_replace( 'generator/templates/static', 'includes/generated', $file['path'] ),
				$this->flysystem->read( $file['path'] )
			);
		}
	}

	public function createType( Type $type ) {
		$this->flysystem->put(
			"includes/generated/{$type->name}.php",
			$this->typeTemplate->render( [ 'type' => $type ] )
		);
	}

	public function createBuilderClass( TypeCollection $types ) {
		$this->flysystem->put(
			'includes/generated/Schema.php',
			$this->builderClassTemplate->render( [ 'types' => $types->toArray() ] )
		);
	}

}
