<?php

namespace PNO\SchemaOrg;

/**
 * The act of generating a comment about a subject.
 *
 * @see http://schema.org/CommentAction
 *
 * @mixin \PNO\SchemaOrg\CommunicateAction
 */
class CommentAction extends BaseType {

	/**
	 * A sub property of result. The Comment created or sent as a result of this
	 * action.
	 *
	 * @param Comment|Comment[] $resultComment
	 *
	 * @return static
	 *
	 * @see http://schema.org/resultComment
	 */
	public function resultComment( $resultComment ) {
		return $this->setProperty( 'resultComment', $resultComment );
	}

}
