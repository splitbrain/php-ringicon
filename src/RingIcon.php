<?php

namespace splitbrain\RingIcon;

/**
 * Class RingIcon
 *
 * Generates a identicon/visiglyph like image based on concentric rings
 *
 * @todo add a mono color version
 * @author Andreas Gohr <andi@splitbrain.org>
 * @license MIT
 * @package splitbrain\RingIcon
 */
class RingIcon
{

    protected $size;
    protected $fullsize;
    protected $rings;
    protected $center;
    protected $ringwidth;
    protected $seed;
    protected $ismono = false;
    protected $monocolor = null;

    /**
     * RingIcon constructor.
     * @param int $size width and height of the resulting image
     * @param int $rings number of rings
     */
    public function __construct($size, $rings = 3)
    {
        $this->size = $size;
        $this->fullsize = $this->size * 5;
        $this->rings = 4;

        $this->center = floor($this->fullsize / 2);
        $this->ringwidth = floor($this->fullsize / $rings);

        $this->seed = mt_rand() . time();
    }

    /**
     * Generates an ring image
     *
     * If a seed is given, the image will be based on that seed
     *
     * @param string $seed initialize the genrator with this string
     * @param string $file if given, the image is saved at that path, otherwise is printed to browser
     */
    public function createImage($seed = '', $file = '')
    {
        if (!$seed) {
            $seed = mt_rand() . time();
        }
        $this->seed = $seed;

        // monochrome wanted?
        if($this->ismono) {
            $this->monocolor = array(
                $this->rand(20,255),
                $this->rand(20,255),
                $this->rand(20,255)
            );
        } else {
            $this->monocolor = null;
        }

        // create
        $image = $this->createTransparentImage($this->fullsize, $this->fullsize);
        $arcwidth = $this->fullsize;
        for ($i = $this->rings; $i > 0; $i--) {
            $this->drawRing($image, $arcwidth);
            $arcwidth -= $this->ringwidth;
        }

        // resample for antialiasing
        $out = $this->createTransparentImage($this->size, $this->size);
        imagecopyresampled($out, $image, 0, 0, 0, 0, $this->size, $this->size, $this->fullsize, $this->fullsize);
        if ($file) {
            imagepng($out, $file);
        } else {
            header("Content-type: image/png");
            imagepng($out);
        }
        imagedestroy($out);
        imagedestroy($image);
    }

    /**
     * When set to true a monochrome version is returned
     *
     * @param bool $ismono
     */
    public function setMono($ismono) {
        $this->ismono = $ismono;
    }

    /**
     * Generate number from seed
     *
     * Each call runs MD5 on the seed again
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    protected function rand($min, $max)
    {
        $this->seed = md5($this->seed);
        $rand = hexdec(substr($this->seed, 0, 8));
        return ($rand % ($max - $min + 1)) + $min;
    }

    /**
     * Drawas a single ring
     *
     * @param resource $image
     * @param int $arcwidth outer width of the ring
     */
    protected function drawRing($image, $arcwidth)
    {
        $color = $this->randomColor($image);
        $transparency = $this->transparentColor($image);

        $start = $this->rand(20, 360);
        $stop = $this->rand(20, 360);
        if($stop < $start) list($start, $stop) = array($stop, $start);

        imagefilledarc($image, $this->center, $this->center, $arcwidth, $arcwidth, $stop, $start, $color, IMG_ARC_PIE);
        imagefilledellipse($image, $this->center, $this->center, $arcwidth - $this->ringwidth,
            $arcwidth - $this->ringwidth, $transparency);

        imagecolordeallocate($image, $color);
        imagecolordeallocate($image, $transparency);
    }

    /**
     * Allocate a transparent color
     *
     * @param resource $image
     * @return int
     */
    protected function transparentColor($image)
    {
        return imagecolorallocatealpha($image, 0, 0, 0, 127);
    }

    /**
     * Allocate a random color
     *
     * @param $image
     * @return int
     */
    protected function randomColor($image)
    {
        if($this->ismono) {
            return imagecolorallocatealpha($image, $this->monocolor[0], $this->monocolor[1], $this->monocolor[2], $this->rand(0, 96));
        }
        return imagecolorallocate($image, $this->rand(0, 255), $this->rand(0, 255), $this->rand(0, 255));
    }

    /**
     * Create a transparent image
     *
     * @param int $width
     * @param int $height
     * @return resource
     * @throws \Exception
     */
    protected function createTransparentImage($width, $height)
    {
        $image = @imagecreatetruecolor($width, $height);
        if (!$image) {
            throw new \Exception('Missing libgd support');
        }
        imagealphablending($image, false);
        $transparency = $this->transparentColor($image);
        imagefill($image, 0, 0, $transparency);
        imagecolordeallocate($image, $transparency);
        imagesavealpha($image, true);
        return $image;
    }

}
