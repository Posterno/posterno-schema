<?php

namespace PNO\SchemaOrg;

/**
 * The act of registering to an organization/service without the guarantee to
 * receive it.
 *
 * Related actions:
 *
 * * [[RegisterAction]]: Unlike RegisterAction, ApplyAction has no guarantees
 * that the application will be accepted.
 *
 * @see http://schema.org/ApplyAction
 *
 * @mixin \PNO\SchemaOrg\OrganizeAction
 */
class ApplyAction extends BaseType {

}
