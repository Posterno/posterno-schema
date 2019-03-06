<?php

namespace Posterno\SchemaOrg;

/**
 * UserInteraction and its subtypes is an old way of talking about users
 * interacting with pages. It is generally better to use [[Action]]-based
 * vocabulary, alongside types such as [[Comment]].
 *
 * @see http://schema.org/UserPageVisits
 *
 * @mixin \Posterno\SchemaOrg\UserInteraction
 */
class UserPageVisits extends BaseType
{
}
