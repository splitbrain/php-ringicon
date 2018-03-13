<?php

require_once 'vendor/autoload.php';

use splitbrain\RingIcon\RingIconSVG;

// define size and number of rings
$ringicon = new RingIconSVG(128, 3);

// decide if monochrome image is wanted
$ringicon->setMono(false);

?>
<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
    <div style="width: 128px; height: 128px; border: 1px solid black;">
        <?php
        echo $ringicon->getInlineSVG();
        ?>
    </div>
</div>
