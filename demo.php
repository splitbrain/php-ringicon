<?php

require_once 'vendor/autoload.php';
use splitbrain\RingIcon\RingIcon;

// define size and number of rings
$ringicon = new RingIcon(128, 3);

// decide if monochrome image is wanted
$ringicon->setMono(false);

// completely random image directly output to browser
$ringicon->createImage();

// example to save an image based on an email address:
// $ringicon->createImage('mail@example.com', '/tmp/avatar.png');