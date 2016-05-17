<?php

class Payone_Protocol_Filter_MaskAllValue
    extends Payone_Protocol_Filter_Abstract {

    const MASK_CHAR = 'x';
    const FILTER_KEY = 'mask_all_value';
    protected $key = self::FILTER_KEY;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filterValue($value)
    {
        return str_repeat(self::MASK_CHAR, strlen($value));
    }
}