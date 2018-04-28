<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 19:44
 */

namespace Oza\UserImagesManager;


use Oza\UserImagesManager\Interfaces\AvatarInterface;

class Avatar extends Manager implements AvatarInterface
{
    /**
     * Get the current avatar object
     **/
    public function getAvatar()
    {
        return !empty($this->all) ?
            $this->all['current'] :
            $this->getAvatars()->current();
    }

    /**
     * Get all avatars
     *
     * @return Avatar
     */
    public function getAvatars() : Avatar
    {
        $avatars = (array)json_decode($this->profile->avatars);
        $avatars = !is_null($avatars) && !empty($avatars) ? $avatars : [];
        $this->all = $avatars;
        return $this;
    }

    /**
     * set a avatar to a profile
     *
     * @param string $src
     * @return string|null
     * @throws Exceptions\ProfileModelNotDefined
     */
    public function setAvatar(string $src)
    {
        $avatars = !empty($this->all) ? $this->all : $this->getAvatars()->all;
        if (empty($avatars)) $this->all = $avatars = $this->getDefaultImageArray($src);
        else if ($this->AlreadyUsed($src)) $this->setAvatarById($this->findIdBySrc($src));
        else $this->all = $avatars = $this->changeCurrent($avatars, $this->newItem($src));

        $this->pushToDb();
        return $this->getAvatar();
    }

    /**
     * Push avatars to Database
     *
     * @throws Exceptions\ProfileModelNotDefined
     */
    private function pushToDb() {
        $this->profile->avatars = json_encode($this->all);
        parent::save();
    }


    /**
     * Set Avatar by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setAvatarById(string $id)
    {
        $item = (array)array_first((array)$this->findInArray($this->others(), $id, 'id'));
        $this->all['current'] = $item;
    }
}