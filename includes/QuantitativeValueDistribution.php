<?php

namespace PNO\SchemaOrg;

/**
 * A statistical distribution of values.
 *
 * @see http://schema.org/QuantitativeValueDistribution
 *
 * @mixin \PNO\SchemaOrg\StructuredValue
 */
class QuantitativeValueDistribution extends BaseType {

	/**
	 * The duration of the item (movie, audio recording, event, etc.) in [ISO
	 * 8601 date format](http://en.wikipedia.org/wiki/ISO_8601).
	 *
	 * @param Duration|Duration[] $duration
	 *
	 * @return static
	 *
	 * @see http://schema.org/duration
	 */
	public function duration( $duration ) {
		return $this->setProperty( 'duration', $duration );
	}

	/**
	 * The median value.
	 *
	 * @param float|float[]|int|int[] $median
	 *
	 * @return static
	 *
	 * @see http://schema.org/median
	 */
	public function median( $median ) {
		return $this->setProperty( 'median', $median );
	}

	/**
	 * The 10th percentile value.
	 *
	 * @param float|float[]|int|int[] $percentile10
	 *
	 * @return static
	 *
	 * @see http://schema.org/percentile10
	 */
	public function percentile10( $percentile10 ) {
		return $this->setProperty( 'percentile10', $percentile10 );
	}

	/**
	 * The 25th percentile value.
	 *
	 * @param float|float[]|int|int[] $percentile25
	 *
	 * @return static
	 *
	 * @see http://schema.org/percentile25
	 */
	public function percentile25( $percentile25 ) {
		return $this->setProperty( 'percentile25', $percentile25 );
	}

	/**
	 * The 75th percentile value.
	 *
	 * @param float|float[]|int|int[] $percentile75
	 *
	 * @return static
	 *
	 * @see http://schema.org/percentile75
	 */
	public function percentile75( $percentile75 ) {
		return $this->setProperty( 'percentile75', $percentile75 );
	}

	/**
	 * The 90th percentile value.
	 *
	 * @param float|float[]|int|int[] $percentile90
	 *
	 * @return static
	 *
	 * @see http://schema.org/percentile90
	 */
	public function percentile90( $percentile90 ) {
		return $this->setProperty( 'percentile90', $percentile90 );
	}

}
