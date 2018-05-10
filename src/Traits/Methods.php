<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager\Traits;


use Oza\UserImagesManager\Exceptions\InvalidIdForManager;
use Oza\UserImagesManager\Exceptions\InvalidValue;
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
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array
    {
        $this->getAll();
        return $this->fetchByField('id', $id);
    }

    /**
     * @param string $src
     * @return array|null
     */
    public function getBySrc(string $src): ?array
    {
        $this->getAll();
        return $this->fetchByField('src', $src);
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
     * @param bool $strict
     * @return array
     */
    public function others(bool $strict = false): array
    {
        if (empty($this->all)) $this->getAll();
        return parent::others($strict);
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
            $this->all['others'] = $this->pushInArray($this->all['others'], (array)$this->all['current']);
            $this->all = $this->changeCurrent($this->all, $item);
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

    /**
     * @param string $id
     * @param null|string $field
     * @param $value
     * @return bool
     * @throws InvalidIdForManager
     * @throws InvalidValue
     */
    public function change(string $id, $value, ?string $field = null): bool
    {
        $data = $this->getById($id);

        if (!$data) throw new InvalidIdForManager("Can't find a record that has $id as id");
        if (is_null($field)) {
            if (is_string($value)) $data['src'] = $value;
            elseif (is_array($value)) $data = $value;
            else throw new InvalidValue("The value must have a string type or array type when you do not specify a field");
        }

        $data[$field] = $value;

        if (((array)$this->all['current'])['id'] === $id) {
            $this->all['current'] = $data;
            $this->pushToDb();
            return true;
        }

        foreach ($this->all['others'] as $key => $item) {
            if (((array)$this->all['others'][$key])["id"] === $id) {
                $this->all['others'][$key] = $data;
                $this->pushToDb();
                return true;
            }
        }
        return false;
    }
}