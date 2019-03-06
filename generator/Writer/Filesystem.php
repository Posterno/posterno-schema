<?php

namespace Posterno\SchemaOrg\Generator\Writer;

use League\Flysystem\Adapter\Local;
use Posterno\SchemaOrg\Generator\Type;
use League\Flysystem\Filesystem as Flysystem;
use Posterno\SchemaOrg\Generator\TypeCollection;

class Filesystem {

	/** @var \League\Flysystem\Filesystem */
	protected $flysystem;

	/** @var \Posterno\SchemaOrg\Generator\Writer\Template */
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
		$this->flysystem->deleteDir( 'includes/classes' );
		$this->flysystem->createDir( 'includes/classes' );
	}

	public function createTypesList( TypeCollection $types ) {
		$this->flysystem->put(
			'includes/classes/types-list.php',
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
				str_replace( 'generator/templates/static', 'includes/classes', $file['path'] ),
				$this->flysystem->read( $file['path'] )
			);
		}
	}

	public function createType( Type $type ) {
		$this->flysystem->put(
			"includes/classes/{$type->name}.php",
			$this->typeTemplate->render( [ 'type' => $type ] )
		);
	}

	public function createBuilderClass( TypeCollection $types ) {
		$this->flysystem->put(
			'includes/classes/Schema.php',
			$this->builderClassTemplate->render( [ 'types' => $types->toArray() ] )
		);
	}

}
