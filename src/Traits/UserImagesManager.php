<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 19:35
 */

namespace Oza\UserImagesManager\Traits;


use Oza\UserImagesManager\Avatar;
use Oza\UserImagesManager\Cover;
use Oza\UserImagesManager\Exceptions\NotAUserProfileModel;
use Oza\UserImagesManager\Manager;

trait UserImagesManager
{
    /**
     * @return Manager
     * @throws NotAUserProfileModel
     */
    public function avatarManager() : Manager
    {
        $instance = new Avatar();
        return $instance->setProfile($this);
    }

    /**
     * @return Manager
     * @throws NotAUserProfileModel
     */
    public function coverManager () : Manager
    {
        $instance = new Cover();
        return $instance->setProfile($this);
    }
}