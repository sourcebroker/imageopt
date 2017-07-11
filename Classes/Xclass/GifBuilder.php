<?php

namespace SourceBroker\Imageopt\Xclass;

use TYPO3\CMS\Frontend\Imaging\GifBuilder as ParentClass;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class GifBuilder extends ParentClass
{
    /***********************************
     *
     * Scaling, Dimensions of images
     *
     ***********************************/
    /**
     * Converts $imagefile to another file in temp-dir of type $newExt (extension).
     *
     * @param string $imagefile The image filepath
     * @param string $newExt New extension, eg. "gif", "png", "jpg", "tif". If $newExt is NOT set, the new imagefile will be of the original format. If newExt = 'WEB' then one of the web-formats is applied.
     * @param string $w Width. $w / $h is optional. If only one is given the image is scaled proportionally. If an 'm' exists in the $w or $h and if both are present the $w and $h is regarded as the Maximum w/h and the proportions will be kept
     * @param string $h Height. See $w
     * @param string $params Additional ImageMagick parameters.
     * @param string $frame Refers to which frame-number to select in the image. '' or 0 will select the first frame, 1 will select the next and so on...
     * @param array $options An array with options passed to getImageScale (see this function).
     * @param bool $mustCreate If set, then another image than the input imagefile MUST be returned. Otherwise you can risk that the input image is good enough regarding messures etc and is of course not rendered to a new, temporary file in typo3temp/. But this option will force it to.
     * @return array [0]/[1] is w/h, [2] is file extension and [3] is the filename.
     * @see getImageScale(), typo3/show_item.php, fileList_ext::renderImage(), \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::getImgResource(), SC_tslib_showpic::show(), maskImageOntoImage(), copyImageOntoImage(), scale()
     */
    public function imageMagickConvert($imagefile, $newExt = '', $w = '', $h = '', $params = '', $frame = '', $options = [], $mustCreate = false)
    {
        if ($params == 'MUST_RECREATE') {
            $params = null;
            $mustCreate = true;
        }
        return parent::imageMagickConvert($imagefile, $newExt, $w, $h, $params, $frame, $options, $mustCreate);
    }
}