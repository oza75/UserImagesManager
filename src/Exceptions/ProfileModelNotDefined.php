<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager\Exceptions;


class ProfileModelNotDefined extends \Exception
{
    protected $message = "You have not defined the Profile Model that should be used! call setProfile() methods before!";
}