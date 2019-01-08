<?php

namespace SourceBroker\Imageopt\Utility;

class ArrayUtility
{
    /**
     * Merge value from array2 into array1 only if it was not already set in array 1
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function arrayMergeAsFallback(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::arrayMergeAsFallback($merged[$key], $value);
            } else {
                if (!isset($merged[$key])) {
                    $merged[$key] = $value;
                }
            }
        }
        return $merged;
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
        while ($part = array_shift($plainArray)) {
            if (empty($plainArray)) {
                $node = $part;
            } else {
                $node[$part] = [];
                $node =& $node[$part];
            }
        }
        return $root;
    }
}
