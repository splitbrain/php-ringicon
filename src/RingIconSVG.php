<?php

namespace splitbrain\RingIcon;


class RingIconSVG extends AbstractRingIcon
{

    /**
     * Generates an ring image svg suitable for inlining in html
     *
     * If a seed is given, the image will be based on that seed
     *
     * @param string $seed
     *
     * @return string
     */
    public function getInlineSVG($seed = '') {
        return $this->generateSVGImage($seed);
    }

    /**
     * Generates an ring image svg
     *
     * If a seed is given, the image will be based on that seed
     *
     * @param string $seed initialize the genrator with this string
     * @param string $file if given, the image is saved at that path, otherwise is printed as file to browser
     */
    public function createImage($seed = '', $file = '') {
        $svg = $this->generateSVGImage($seed, true);
        if ($file) {
            file_put_contents($file, $svg);
        } else {
            header("Content-type: image/svg+xml");
            echo $svg;
        }
    }

    /**
     * Generates an ring image svg
     *
     * If a seed is given, the image will be based on that seed
     *
     * @param string $seed  initialize the genrator with this string
     * @param bool   $file  if true, the svg will have the markup suitable for being delivered as file
     *
     * @return string
     */
    protected function generateSVGImage($seed = '', $file = false) {
        if (!$seed) {
            $seed = mt_rand() . time();
        }
        $this->seed = $seed;

        // monochrome wanted?
        if($this->ismono) {
            $this->generateMonoColor();
        }

        $svgFileAttributes = $file ?
            'xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"'
            : ''
        ;

        $svg = "<svg $svgFileAttributes viewBox=\"0 0 $this->fullsize $this->fullsize\">";
        $svg .= "<!-- seed to generate this svg: $this->seed -->";
        $arcOuterRadious = $this->fullsize/2;
        for ($i = $this->rings; $i > 0; $i--) {
            $svg .= $this->createSVGArcPath($arcOuterRadious);
            $arcOuterRadious -= $this->ringwidth/2;
        }
        $svg .= '</svg>';

        if ($file) {
            $svgBoilerplate = '?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
            $svg = $svgBoilerplate . $svg;
        }

        return $svg;
    }

    /**
     * Draw a single arc
     *
     * @param float $outerRadius
     *
     * @return string
     */
    protected function createSVGArcPath($outerRadius) {
        $color = $this->randomCssColor();
        $ringThickness = $this->ringwidth/2;
        $startAngle = $this->rand(20, 360);
        $stopAngle = $this->rand(20, 360);
        if($stopAngle < $startAngle) {
            list($startAngle, $stopAngle) = array($stopAngle, $startAngle);
        }

        list ($xStart, $yStart) = $this->polarToCartesian($outerRadius, $startAngle);
        list ($xOuterEnd, $yOuterEnd) = $this->polarToCartesian($outerRadius, $stopAngle);

        $innerRadius = $outerRadius - $ringThickness;
        $SweepFlag = 0;
        $innerSweepFlag = 1;
        $largeArcFlag = (int) ($stopAngle-$startAngle < 180);
        list ($xInnerStart, $yInnerStart) = $this->polarToCartesian($outerRadius - $ringThickness, $stopAngle);
        list ($xInnerEnd, $yInnerEnd) = $this->polarToCartesian($outerRadius - $ringThickness, $startAngle);

        $fullPath = "<path fill='$color' stroke=\"#000000\" d='
            M $xStart $yStart
            A $outerRadius $outerRadius 0 $largeArcFlag $SweepFlag $xOuterEnd $yOuterEnd
            L $xInnerStart $yInnerStart
            A $innerRadius $innerRadius 0 $largeArcFlag $innerSweepFlag $xInnerEnd $yInnerEnd
            Z
            '/>";
        return $fullPath;
    }

    /**
     * Create a random valid css color value
     *
     * @return string
     */
    protected function randomCssColor() {
        if($this->ismono) {
            $alpha = 1 - $this->rand(0, 96)/100;
            return "rgba({$this->monocolor[0]}, {$this->monocolor[1]}, {$this->monocolor[2]}, $alpha)";
        }
        $r = $this->rand(0, 255);
        $g = $this->rand(0, 255);
        $b = $this->rand(0, 255);
        return "rgb($r, $g, $b)";
    }

    /**
     * Calculate the x,y coordinate of a given angle at a given distance from the center
     *
     * 0 Degree is 3 o'clock
     *
     * @param float $radius
     * @param float $angleInDegrees
     *
     * @return array
     */
    protected function polarToCartesian($radius, $angleInDegrees)
    {
        $angleInRadians = $angleInDegrees * M_PI / 180.0;

        return array(
            $this->fullsize/2 + ($radius * cos($angleInRadians)),
            $this->fullsize/2 + ($radius * sin($angleInRadians)),
        );
    }

}
