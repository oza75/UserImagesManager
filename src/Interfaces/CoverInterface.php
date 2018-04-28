<?php
/**
 * Created by PhpStorm.
 * User: oza
 * Date: 28/04/18
 * Time: 08:09
 */

namespace Oza\UserImagesManager\Interfaces;


use Oza\UserImagesManager\Cover;

interface CoverInterface
{
    /**
     * Get the current cover object
     *
     * @return mixed
     */
    public function getCover();

    /**
     * Get all covers
     *
     * @return Cover
     */
    public function getCovers() : Cover;

    /**
     * set cover to a profile
     *
     * @param string $src
     * @return string|null
     */
    public function setCover(string $src);

    /**
     * Set cover by an id
     *
     * @param string $id
     * @return mixed
     */
    public function setCoverById(string $id);
}