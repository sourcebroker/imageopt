<?php

namespace SourceBroker\Imageopt\Utility;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

class ArrayUtility
{
    /**
     * Merge value from array2 into array1 only if it was not already set in array 1
     */
    public static function arrayMergeAsFallback(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
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
     */
    public static function plainToNested(array $plainArray): array
    {
        $root = [];
        $node =& $root;
        $part = array_shift($plainArray);
        while ($part !== null) {
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
