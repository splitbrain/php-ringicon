<?php

namespace splitbrain\RingIcon;

/**
 * Class RingIcon
 *
 * Generates a identicon/visiglyph like image based on concentric rings
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @license MIT
 * @package splitbrain\RingIcon
 */
abstract class AbstractRingIcon
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
        $this->rings = $rings;

        $this->center = floor($this->fullsize / 2);
        $this->ringwidth = floor($this->fullsize / $rings);

        $this->seed = mt_rand() . time();
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
     * Set a fixed color, which to be later only varied within its alpha channel
     */
    protected function generateMonoColor()
    {
        $this->monocolor = array(
            $this->rand(20, 255),
            $this->rand(20, 255),
            $this->rand(20, 255)
        );
    }
}
