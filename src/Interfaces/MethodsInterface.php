<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager\Interfaces;


interface MethodsInterface
{
    /**
     * Get the current  object
     *
     * @return mixed
     */
    public function current(): string;

    /**
     * @return array
     */
    public function currentInArray(): array;

    /**
     * Get all
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get Others
     *
     * @return array
     *
     */
    public function others(): array;

    /**
     *  Fill All Array and return an instance
     *
     * @return MethodsInterface
     */
    public function getAll(): MethodsInterface;

    /**
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * @param string $id
     * @return array|null
     */
    public function getBySrc(string $id): ?array;

    /**
     * set to profile
     *
     * @param string $src
     * @return string|null
     */
    public function set(string $src): string;

    /**
     * Set Random image
     *
     * @return string
     */
    public function setRandom(): string;

    /**
     * Set by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setById(string $id): ?string;

    /**
     * @param string $id
     * @return bool
     */
    public function remove(string $id): bool;

    /**
     * @return bool
     */
    public function removeAll(): bool;

    /**
     * @param string $id
     * @param null|string $field
     * @param $value
     * @return bool
     */
    public function change(string $id,  $value,?string $field= null) : bool;
}