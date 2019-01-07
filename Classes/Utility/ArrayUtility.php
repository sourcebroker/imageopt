<?php

namespace SourceBroker\Imageopt\Utility;

class ArrayUtility
{
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
