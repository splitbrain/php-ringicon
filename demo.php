<?php

require_once 'vendor/autoload.php';
use splitbrain\RingIcon\RingIcon;

$ringicon = new RingIcon(128);

// completely random image directly output to browser
$ringicon->createImage();

// example to save an image based on an email address:
// $ringicon->createImage('mail@example.com', '/tmp/avatar.png');