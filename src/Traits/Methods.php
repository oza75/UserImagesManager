<?php
/**
 * Created by PhpStorm.
 * User: oza
 * Date: 29/04/18
 * Time: 09:45
 */

namespace Oza\UserImagesManager\Traits;


use Oza\UserImagesManager\Interfaces\MethodsInterface;

trait Methods
{
    /**
     * Get the current
     *
     * @return string
     **/
    public function current(): string
    {
        return !empty($this->all) ?
            ((array)$this->all['current'])['src'] :
            $this->getAll()->current();
    }

    /**
     * @return array
     */
    public function currentInArray(): array
    {
        $this->getAll();
        return !empty($this->all) ? (array)$this->all['current'] : [];
    }

    /**
     * Get all data
     *
     * @return MethodsInterface
     */
    public function getAll(): MethodsInterface
    {
        $data = (array)json_decode($this->profile->{$this->field});
        $data = !is_null($data) && !empty($data) ? $data : [];
        $this->all = $data;
        return $this;
    }

    /**
     * Get an Array of data
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getAll()->all;
    }

    /**
     * Get others
     *
     * @return array
     */
    public function others(): array
    {
        if (empty($this->all)) $this->getAll();
        return parent::others();
    }

    /**
     * set to  profile
     *
     * @param string $src
     * @return string|null
     */
    public function set(string $src): string
    {
        $data = !empty($this->all) ? $this->all : $this->all();
        if (empty($data)) $this->all = $data = $this->getDefaultImageArray($src);
        else if ($this->AlreadyUsed($src)) $this->setById($this->findIdBySrc($src));
        else $this->all = $data = $this->changeCurrent($data, $this->newItem($src));

        $this->pushToDb();
        return $this->current();
    }

    /**
     * Set a random image
     *
     * @return string
     */
    public function setRandom(): string
    {
        $domain = $this->defaultApiAddress[-1] === '/' ?
            $this->defaultApiAddress :
            $this->defaultApiAddress . '/';
        $src = $domain . 'random/' . $this->defaultSize;

        return $this->set($src);
    }

    /**
     * Push data to Database
     */
    private function pushToDb()
    {
        $this->profile->{$this->field} = json_encode($this->all);
        parent::save();
    }


    /**
     * Set  by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setById(string $id): ?string
    {
        $item = (array)array_first((array)$this->findInArray($this->others(), $id, 'id'));
        if (!empty($item)) {
            $this->all['current'] = $item;
            return $this->current();
        }
        return null;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function remove(string $id): bool
    {
        $this->getAll();
        parent::removeItem($id);
        return true;
    }

    public function removeAll(): bool
    {
        $this->all = null;
        $this->pushToDb();
        return true;
    }
}