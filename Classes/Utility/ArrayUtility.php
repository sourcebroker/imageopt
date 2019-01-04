<?php

namespace SourceBroker\Imageopt\Utility;

class ArrayUtility
{

    public static function mergeRecursiveDistinct(array $array1, array $array2)
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = static::mergeRecursiveDistinct($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}
