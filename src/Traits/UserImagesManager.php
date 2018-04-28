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

trait UserImagesManager
{
    /**
     * @return Avatar
     * @throws NotAUserProfileModel
     */
    public function avatarManager() : Avatar {
        $instance = new Avatar();
        $instance->setProfile($this);
        return $instance;
    }

    /**
     * @return Cover
     * @throws NotAUserProfileModel
     */
    public function coverManager () : Cover {
        $instance = new Cover();
        $instance->setProfile($this);
        return $instance;
    }
}