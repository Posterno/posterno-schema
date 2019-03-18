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

		$this->typesListTemplate      = new Template( 'types-list.php.twig' );
		$this->propertiesListTemplate = new Template( 'properties-list.php.twig' );
		$this->typeTemplate           = new Template( 'Type.php.twig' );
		$this->builderClassTemeplate   = new Template( 'Schema.php.twig' );
	}

	public function clearAutomatedFiles() {

		$contents = $this->flysystem->listContents( 'includes', true );

		$excluded = [ 'types-list.php', 'properties-list.php' ];
		$excluded_paths = [ 'includes/Settings', 'includes/Tag' ];

		foreach ( $contents as $object ) {
			if ( strpos( $object['path'], 'includes/Settings' ) === 0 || strpos( $object['path'], 'includes/Tag' ) === 0 ) {
				continue;
			} elseif ( strpos( $object['path'], 'includes/Component' ) === 0 && ! in_array( $object['basename'], $excluded ) ) {
				continue;
			} else {
				$this->flysystem->delete( $object['path'] );
			}
		}
	}

	public function createTypesList( TypeCollection $types ) {

		$types      = $types->toArray();
		$main_types = [];

		foreach ( $types as $type ) {
			if ( isset( $type->parents ) && is_array( $type->parents ) && ! empty( $type->parents ) ) {
				if ( in_array( 'Thing', $type->parents ) ) {
					$main_types[] = $type;
				}
			} else {
				continue;
			}
		}

		$this->flysystem->put(
			'includes/Component/admin/types-list.php',
			$this->typesListTemplate->render( [ 'types' => $main_types ] )
		);
	}

	public function createPropertiesList( TypeCollection $types ) {

		$this->flysystem->put(
			'includes/Component/admin/properties-list.php',
			$this->propertiesListTemplate->render( [ 'properties' => $types->propertiesList ] )
		);

	}

	public function cloneStaticFiles() {
		$files = $this->flysystem->listContents( 'generator/templates/static', true );

		foreach ( $files as $file ) {
			if ( $file['type'] !== 'file' ) {
				continue;
			}

			$this->flysystem->put(
				str_replace( 'generator/templates/static', 'includes', $file['path'] ),
				$this->flysystem->read( $file['path'] )
			);
		}
	}

	public function createType( Type $type ) {
		$this->flysystem->put(
			"includes/{$type->name}.php",
			$this->typeTemplate->render( [ 'type' => $type ] )
		);
	}

	public function createBuilderClass( TypeCollection $types ) {
		$this->flysystem->put(
			'includes/Schema.php',
			$this->builderClassTemplate->render( [ 'types' => $types->toArray() ] )
		);
	}

}
