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

		$this->typesListTemplate = new Template( 'types-list.php.twig' );
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
}
