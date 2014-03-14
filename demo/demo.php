<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

require_once __DIR__ . '/../src/autoload.php';

$fcrop = \FcropPhp\Crop::getInstance();
$fcrop->setQuality(80);

$image = $fcrop->loadImage(__DIR__ . '/in.jpg');
$image->setFocusPoint(2596, 1100);
$image->setPreferredSize(800, 400);

$fcrop->process($image);

$image->save(__DIR__ . '/out.jpg');