<?php
/**
 * Created by PhpStorm.
 * User: oza
 * Date: 28/04/18
 * Time: 08:09
 */

namespace Oza\UserImagesManager;


use Oza\UserImagesManager\Interfaces\CoverInterface;

class Cover extends Manager implements CoverInterface
{
    /**
     * Get the current cover object
     **/
    public function getCover()
    {
        return !empty($this->all) ?
            $this->all['current'] :
            $this->getCovers()->current();
    }

    /**
     * Get all covers
     *
     * @return Cover
     */
    public function getCovers() : Cover
    {
        $covers = (array)json_decode($this->profile->covers);
        $covers = !is_null($covers) && !empty($covers) ? $covers : [];
        $this->all = $covers;
        return $this;
    }

    /**
     * set a cover to a profile
     *
     * @param string $src
     * @return string|null
     * @throws Exceptions\ProfileModelNotDefined
     */
    public function setCover(string $src)
    {
        $covers = !empty($this->all) ? $this->all : $this->getCovers()->all;
        if (empty($covers)) $this->all = $covers = $this->getDefaultImageArray($src);
        else if ($this->AlreadyUsed($src)) $this->setCoverById($this->findIdBySrc($src));
        else $this->all = $covers = $this->changeCurrent($covers, $this->newItem($src));

        $this->pushToDb();
        return $this->getCover();
    }

    /**
     * Push covers to Database
     *
     * @throws Exceptions\ProfileModelNotDefined
     */
    private function pushToDb() {
        $this->profile->covers = json_encode($this->all);
        parent::save();
    }


    /**
     * Set Cover by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setCoverById(string $id)
    {
        $item = (array)array_first((array)$this->findInArray($this->others(), $id, 'id'));
        $this->all['current'] = $item;
    }
}