<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Oza\UserImagesManager\Exceptions\NotAUserProfileModel;
use Oza\UserImagesManager\Exceptions\ProfileModelNotDefined;
use Oza\UserImagesManager\Interfaces\ManagerInterface;
use Oza\UserImagesManager\Utils\StdClassOrString;

class Manager implements ManagerInterface
{

    public $saved = false;
    protected $profile;
    protected $user_id_field = "user_id";
    protected $all = [];
    protected $current = [];
    protected $toParse = null;
    protected $defaultApiAddress;

    public function __construct()
    {
        $this->user_id_field = config('profile.user_id_field');
        $this->defaultApiAddress = config('profile.default_images_domain');
    }

    /**
     * Return a default representation of our Empty Image Array
     * @param string $src
     * @return array
     */
    public function getDefaultImageArray(string $src): array
    {
        $img = $this->newItem($src);
        return [
            'current' => $img,
            'others' => []
        ];
    }

    /**
     * Find an item to an array with his unique id
     *
     * @param array $data
     * @param string $needed
     * @param string $field
     * @return mixed
     */
    public function findInArray(array $data, string $needed, string $field)
    {
        return array_values(array_filter($data, function ($item) use ($needed, $field) {
            $item = (array)$item;
            return $item[$field] === $needed;
        }));
    }

    /**
     * Retrieve an Item from collection `All`
     *
     * @param string $field
     * @param $needed
     * @return array|null
     */

    public function fetchByField(string $field, $needed): ?array
    {
        if (((array)$this->all['current'])[$field] === $needed) return (array)$this->all['current'];
        $item = $this->findInArray($this->others(), $needed, $field);
        if (!empty($item)) return (array)$item[0];
        return null;
    }

    /**
     * check if an array has an item that has an id equals to
     * @param array $array
     * @param string $needed
     * @param string $field
     * @return bool
     */
    public function inArray(array $array, string $needed, string $field): bool
    {
        $needed = $this->findInArray($array, $needed, $field);
        return !empty($needed);
    }

    /**
     * Push an item into an array
     *
     * @param array $data
     * @param array $item
     * @return array
     */
    public function pushInArray(array $data, array $item): array
    {
        if (!$this->inArray($data, $item['id'], "id")) {
            array_push($data, $item);
        }
        return $data;
    }

    /**
     * Save modification into db
     *
     * @return mixed
     * @throws ProfileModelNotDefined
     */
    public function save()
    {
        $this->checkModel();
        if (!is_null($this->profile)) $this->saved = $this->profile->save();
    }

    public function saved(): bool
    {
        return $this->saved;
    }

    /**
     * Set A profile to Manager
     *
     * @param Model $model
     * @return Manager
     * @throws NotAUserProfileModel
     */
    public function setProfile(Model $model): Manager
    {
        if ($this->isUserProfile($model)) $this->profile = $model;
        else throw new NotAUserProfileModel("The model passed in parameter does not contain a field {$this->user_id_field}");
        return $this;
    }

    /**
     * Check that the model that was passed in parameter of
     * setProfile methods contains a user_id field
     *
     * @param Model $model
     * @return bool
     */
    public function isUserProfile(Model $model): bool
    {
        return isset($model->{$this->user_id_field}) && !is_null($model->{$this->user_id_field});
    }

    /**
     * Check if the model is not null
     *
     * @return bool
     * @throws ProfileModelNotDefined
     */
    public function checkModel(): bool
    {
        if (is_null($this->profile)) throw new ProfileModelNotDefined();
        else return true;
    }

    /**
     * @param bool $strict
     * @return array
     */
    public function others(bool $strict = false): array
    {
        $others = $this->all['others'] ?? [];
        if($strict === false) return $others;

        if (empty($others)) return $others;
        $current = (array)$this->all['current'];
        $current = $current['id'];
        $others = array_filter($others, function ($item) use ($current) {
            $item = (array)$item;
            return $item["id"] !== $current;
        });

        return $others;
    }

    public function removeItem(string $id): void
    {
        $data = $this->all;
        if ($data['current']->id === $id) {
            for ($i = count($data['others']); $i > 0; $i--) {
                $others = (array)$data['others'];
                if ($others[$i - 1]->id !== $id) {
                    $data['current'] = $others[$i - 1];
                }
            }
        }
        $data['others'] = array_filter($data['others'], function ($item) use ($id) {
            return $item->id !== $id;
        });
        $this->all = $data;
    }

    /**
     * Get the current image
     *
     * @return string|null
     */
    public function current()
    {
        $this->toParse = $this->current = $this->all['current'] ?? [];
        return StdClassOrString::put($this->toParse, '-', 'src')->toString();
    }

    /**
     * Generate a new item
     *
     * @param string $src
     * @return array
     */
    public function newItem(string $src): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'src' => $src,
            'set_at' => Carbon::now()->format('Y-m-d')
        ];
    }

    /**
     * Change the current image and add it to others array
     *
     * @param array $data
     * @param array $newItem
     * @return array
     */
    public function changeCurrent(array $data, array $newItem): array
    {
        $current = $data['current'];
        $data['current'] = $newItem;
        $data['others'] = $this->pushInArray($data['others'], (array)$current);
        return $data;
    }

    /**
     * Check if an image is already used or not
     *
     * @param string $src
     * @return bool
     */
    public function AlreadyUsed(string $src): bool
    {
        return $this->inArray($this->others(), $src, 'src');
    }

    /**
     * Find an id with source
     *
     * @param string $src
     * @return string
     */
    public function findIdBySrc(string $src): ?string
    {
        $item = (array)array_first((array)$this->findInArray($this->others(), $src, 'src'));
        if ($item) return $item["id"];
        return null;
    }
}