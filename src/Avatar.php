<?php

/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager;

use Oza\UserImagesManager\Interfaces\MethodsInterface;
use Oza\UserImagesManager\Traits\Methods;

class Avatar extends Manager implements MethodsInterface
{

    use Methods;

    private $field;
    private $defaultSize;

    /**
     * Avatar constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->field = config("profile.avatars_field") ?? 'avatars';
        $this->defaultSize = config("profile.default_avatar_size") ?? '400x400';

    }

}