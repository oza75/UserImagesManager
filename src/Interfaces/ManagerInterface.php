<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 19:34
 */

namespace Oza\UserImagesManager\Interfaces;


use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Oza\UserImagesManager\Exceptions\NotAUserProfileModel;
use Oza\UserImagesManager\Exceptions\ProfileModelNotDefined;
use Oza\UserImagesManager\Manager;

interface ManagerInterface
{
    /**
     * Set A profile to Manager
     *
     * @param Model $model
     * @return Manager
     * @throws NotAUserProfileModel
     */
    public function setProfile(Model $model): Manager;

    /**
     * Check that the model that was passed in parameter of
     * setProfile methods contains a user_id field
     *
     * @param Model $model
     * @return bool
     */
    public function isUserProfile(Model $model): bool;

    /**
     * Check if the model is not null
     *
     * @return bool
     * @throws ProfileModelNotDefined
     */
    public function checkModel(): bool;

    /**
     * @param string $src
     * @return array
     */
    public function getDefaultImageArray(string $src): array;


    /**
     * Find an item to an array with his unique id
     *
     * @param array $data
     * @param string $needed
     * @param string $field
     * @return mixed
     */
    public function findInArray(array $data, string $needed, string $field);

    /**
     * check if an array has an item that has an id equals to
     * @param array $array
     * @param string $needed
     * @param string $field
     * @return bool
     */
    public function inArray(array $array, string $needed, string $field): bool;

    /**
     * Push an item into an array
     *
     * @param array $data
     * @param array $item
     * @return array
     */
    public function pushInArray(array $data, array $item): array;

    /**
     * Save modification into db
     *
     * @return mixed
     * @throws ProfileModelNotDefined
     */
    public function save();

    /**
     * Generate a new item
     *
     * @param string $src
     * @return array
     */
    public function newItem(string $src): array;

    /**
     * Change the current image and add it to others array
     * @param array $data
     * @param array $newItem
     * @return array
     */
    public function changeCurrent(array $data, array $newItem) : array;

    /**
     * Check if an image is already used or not
     *
     * @param string $src
     * @return bool
     */
    public function AlreadyUsed(string $src) : bool;

    /**
     * Find an id with source
     *
     * @param string $src
     * @return string
     */
    public function findIdBySrc(string $src) : ?string;
}