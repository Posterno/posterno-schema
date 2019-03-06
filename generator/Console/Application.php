<?php

namespace Posterno\SchemaOrg\Generator\Console;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication {

	public function __construct() {
		parent::__construct( 'Posterno\SchemaOrg package generator', '1.0.0' );

		$this->add( new GenerateCommand() );
	}

	public function getLongVersion() {
		return parent::getLongVersion() . ' by <comment>Posterno</comment>';
	}
}
