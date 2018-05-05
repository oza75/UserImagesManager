<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
 */

namespace Oza\UserImagesManager\Utils;


class StdClassOrString
{
    public $value = null;
    private $stringFields = [];
    private $separator;
    public static function put($data, string $separator = "-", ...$args)
    {
        $this_ = (new self);
        $this_->value = $data;

        $this_->stringFields = $args[0] !== "*" ? $args : array_keys((array)$data);

        $this_->separator = "-";
        return $this_;
    }

    /**
     * Transform this class to string
     *
     * @return array|mixed|null|string
     */
    public function toString()
    {
        if (is_object($this->value)) $this->value = $this->toArray();
        if (!$this->value) return null;
        $string = '';

        if (count(array_keys($this->stringFields)) > 1) {
            foreach ($this->stringFields as $k => $field) {
                $string .= $this->value[$field];
                if ($k < (count($this->stringFields) - 1)) $string .= $this->separator;
            }
        } else if (is_array($this->value)) {
            $string = $this->value[$this->stringFields[0]];
        } else $string = $this->value;

        return $string;
    }

    public function toArray(): array
    {
        return (array)$this->value;
    }

}