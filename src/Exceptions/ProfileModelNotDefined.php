<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 20:26
 */

namespace Oza\UserImagesManager\Exceptions;


class ProfileModelNotDefined extends \Exception
{
    protected $message = "You have not defined the Profile Model that should be used! call setProfile() methods before!";
}