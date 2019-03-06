<?php

namespace Posterno\SchemaOrg;

/**
 * The act of gaining ownership of an object from an origin. Reciprocal of
 * GiveAction.
 * 
 * Related actions:
 * 
 * * [[GiveAction]]: The reciprocal of TakeAction.
 * * [[ReceiveAction]]: Unlike ReceiveAction, TakeAction implies that ownership
 * has been transfered.
 *
 * @see http://schema.org/TakeAction
 *
 * @mixin \Posterno\SchemaOrg\TransferAction
 */
class TakeAction extends BaseType
{
}
