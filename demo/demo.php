<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

require_once __DIR__ . '/../src/autoload.php';

$fcrop = \FcropPhp\Crop::getInstance();
$fcrop->setQuality(80);

$image = $fcrop->loadImage('in.jpg');
$image->setFocusPoint(1000, 1100);
$image->setPreferredSize(1900, 200);

$fcrop->process($image);

$image->save('out.jpg');