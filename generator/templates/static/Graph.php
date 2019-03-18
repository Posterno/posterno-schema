<?php

namespace PNO\SchemaOrg;

use ReflectionClass;
use BadMethodCallException;
use PNO\SchemaOrg\Exceptions\InvalidType;
use PNO\SchemaOrg\Exceptions\TypeNotInGraph;
use PNO\SchemaOrg\Exceptions\TypeAlreadyInGraph;

/**
 * @mixin \PNO\SchemaOrg\Schema
 */
class Graph extends BaseType {

	/** @var array */
	protected $hidden = [];

	public function __call( string $method, array $arguments ) {
		if ( is_callable( [ Schema::class, $method ] ) ) {
			$type = ( new ReflectionClass( Schema::class ) )->getMethod( $method )->getReturnType();

			$schema = $this->getOrCreate( $type );

			if ( isset( $arguments[0] ) && is_callable( $arguments[0] ) ) {
				call_user_func( $arguments[0], $schema, $this );

				return $this;
			}

			return $schema;
		}

		throw new BadMethodCallException( sprintf( 'The method "%" does not exist on class "%s".', $method, get_class( $this ) ) );
	}

	public function add( $schema ) {
		$type = get_class( $schema );

		if ( $this->has( $type ) ) {
			throw new TypeAlreadyInGraph( sprintf( 'The graph already has an item of type "%s".', $type ) );
		}

		return $this->set( $schema );
	}

	public function has( $type ) {
		return $this->offsetExists( $type );
	}

	public function set( $schema ) {
		return $this->setProperty( get_class( $schema ), $schema );
	}

	public function get( $type ) {
		if ( ! $this->has( $type ) ) {
			throw new TypeNotInGraph( sprintf( 'The graph does not have an item of type "%s".', $type ) );
		}

		return $this->getProperty( $type );
	}

	public function getOrCreate( $type ) {
		if ( ! is_subclass_of( $type, Type::class ) ) {
			throw new InvalidType( sprintf( 'The given type "%s" is not an instance of "%s".', $type, Type::class ) );
		}

		if ( ! $this->has( $type ) ) {
			$this->set( new $type() );
		}

		return $this->get( $type );
	}

	public function hide( $type ) {
		$this->hidden[ $type ] = true;

		return $this;
	}

	public function show( $type ) {
		$this->hidden[ $type ] = false;

		return $this;
	}

	public function toArray() {
		$properties = $this->getProperties();

		foreach ( $this->hidden as $type => $hide ) {
			if ( $hide ) {
				unset( $properties[ $type ] );
			}
		}

		return [
			'@context' => $this->getContext(),
			'@graph'   => $this->serializeProperty( array_values( $properties ) ),
		];
	}
}
