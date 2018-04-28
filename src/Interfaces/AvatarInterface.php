<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 20:07
 */

namespace Oza\UserImagesManager\Interfaces;


use Oza\UserImagesManager\Avatar;

interface AvatarInterface
{
    /**
     * Get the current avatar object
     *
     * @return mixed
     */
    public function getAvatar();

    /**
     * Get all avatars
     *
     * @return Avatar
     */
    public function getAvatars() : Avatar;

    /**
     * set avatar to a profile
     *
     * @param string $src
     * @return string|null
     */
    public function setAvatar(string $src);

    /**
     * Set Avatar by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setAvatarById(string $id);
}