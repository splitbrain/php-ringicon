<?php
/**
 * Simple example to show how the library is used
 *
 * Generates the image and sends it back to the browser
 */

require_once 'vendor/autoload.php';

use splitbrain\RingIcon\RingIcon;
use splitbrain\RingIcon\RingIconSVG;

// define size and number of rings
if (empty($_GET['svg'])) {
    $ringicon = new RingIcon(128, 3);
} else {
    $ringicon = new RingIconSVG(128, 3);
}

// decide if monochrome image is wanted
$ringicon->setMono(!empty($_GET['mono']));

// image output directly to browser (seed optionally from parameter)
$ringicon->createImage(isset($_GET['seed']) ? $_GET['seed'] : '');

// example to save an image based on an email address:
// $ringicon->createImage('mail@example.com', '/tmp/avatar.png');