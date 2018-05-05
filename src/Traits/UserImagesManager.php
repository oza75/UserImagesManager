<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
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