<?php

namespace SourceBroker\Imageopt\Utility;

class ArrayUtility
{

    /**
     * Merges 2 arrays without creating arrays from 2 simple values (std array_merge_recursive bug)
     *
     * @param array $array1 Original array
     * @param array $array2 Override array
     * @return array
     */
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

    /**
     * Converts plain array into nested one
     *
     * @param array $plainArray
     * @return array
     */
    public static function plainToNested(array $plainArray)
    {
        $root = [];
        $node =& $root;

        while($part = array_shift($plainArray))
        {
            if(!$plainArray) {
                $node = $part;
            } else {
                $node[ $part ] = [];
                $node =& $node[ $part ];
            }
        }

        return $root;
    }
}
