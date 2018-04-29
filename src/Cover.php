<?php
/**
 * Created by PhpStorm.
 * User: oza
 * Date: 28/04/18
 * Time: 08:09
 */

namespace Oza\UserImagesManager;


use Oza\UserImagesManager\Interfaces\MethodsInterface;
use Oza\UserImagesManager\Traits\Methods;

class Cover extends Manager implements MethodsInterface
{
    use Methods;
    private $field;
    private $defaultSize;

    /**
     * Cover constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->field = config('profile.covers_field') ?? 'covers';
        $this->defaultSize = config('profile.default_cover_size') ?? '1000x800';
    }
}