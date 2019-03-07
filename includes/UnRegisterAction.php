<?php

namespace PNO\SchemaOrg;

/**
 * The act of un-registering from a service.
 * 
 * Related actions:
 * 
 * * [[RegisterAction]]: antonym of UnRegisterAction.
 * * [[LeaveAction]]: Unlike LeaveAction, UnRegisterAction implies that you are
 * unregistering from a service you werer previously registered, rather than
 * leaving a team/group of people.
 *
 * @see http://schema.org/UnRegisterAction
 *
 * @mixin \PNO\SchemaOrg\InteractAction
 */
class UnRegisterAction extends BaseType
{
}
