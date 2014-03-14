<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

namespace FcropPhp;


use FcropPhp\Image\AImage;
use FcropPhp\Image\Driver\ImageMagick;

/**
 * Class Crop
 *
 * Usage:
 *
 * $fcrop = \FcropPhp\Crop::getInstance()
 * $fcrop->setDriver(\FcropPhp\Crop::DRIVER_GMAGICK) // default is \FcropPhp\Crop::DRIVER_IMAGE_MAGICK
 *
 * $image = $fcrop->loadImage(/path/to/image);
 * $image->setFocusPoint(2499, 1200); // setting the focus point
 * $image->setPreferredSize(300, 300); // setting the output image size
 *
 * $fcrop->process($image);
 *
 * $image->save(/path/to/cropped/image);
 *
 *
 */
class Crop {

	const DRIVER_IMAGE_MAGICK = 'ImageMagick';

	const DEFAULT_DRIVER = self::DRIVER_IMAGE_MAGICK;

	const DEFAULT_QUALITY = 70;

	/**
	 * @var string Driver by mean image will be processed
	 */
	private $driver;

	/**
	 * @var AImage Represents image object that will be processed
	 */
	private $image;

	/**
	 * The quality of output image
	 *
	 * @var int Must be in interval 0-100
	 */
	private $quality;

	/**
	 * @var Crop Store self instance
	 */
	private static $instance;

	/**
	 * Disable creating instance via `new` operator
	 */
	private function __construct()
	{
		$this->driver = self::DEFAULT_DRIVER;
		$this->quality = self::DEFAULT_QUALITY;
	}

	/**
	 * Disable ability to clone object
	 */
	private function __clone()
	{

	}

	/**
	 * Disable object serialization
	 */
	private function __sleep()
	{

	}

	/**
	 * Disable object unserialization
	 */
	private function __wakeup()
	{

	}

	/**
	 * Creating an object instance
	 * @return Crop
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns array of available drivers
	 *
	 * @return array
	 */
	public static function availableDrivers()
	{
		return array(
			self::DRIVER_IMAGE_MAGICK
		);
	}

	/**
	 * Setter for $driver property
	 *
	 * @param string $driver
	 * @throws \InvalidArgumentException
	 */
	public function setDriver($driver)
	{
		if (!in_array($driver, static::availableDrivers())) {
			throw new \InvalidArgumentException('Incorrect driver. Available drivers: ' .
				var_export(static::availableDrivers(), true));
		}
		$this->driver = $driver;
	}

	/**
	 * Setter for $quality property
	 *
	 * @param int $quality
	 * @throws \InvalidArgumentException
	 */
	public function setQuality($quality)
	{
		$quality = (int)$quality;
		if ($quality < 0 || $quality > 100) {
			throw new \InvalidArgumentException('Quality must be in interval 0-100');
		}
	}

	/**
	 * @throws \RuntimeException
	 * @return AImage
	 */
	private function createImage()
	{
		switch ($this->driver) {
			case self::DRIVER_IMAGE_MAGICK:
				return new ImageMagick();
			default:
				throw new \RuntimeException('Can not create image instance');
		}
	}

	/**
	 * @param string $filePath Valid path to image that will be processed
	 *
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 * @return AImage
	 */
	public function loadImage($filePath)
	{
		if (!is_file($filePath)) {
			throw new \InvalidArgumentException("$filePath is not a file");
		}
		if (!is_readable($filePath)) {
			throw new \RuntimeException("$filePath should should be a readable");
		}
		$image = $this->createImage();
		$image->load($filePath);

		return $image;
	}

	/**
	 * Process image
	 *
	 * @param AImage $image
	 */
	public function process(AImage $image)
	{
		$size = $image->getSize();

		// destination proportion
		$k = $image->getOutputWidth() / $image->getOutputHeight();

		// define image for crop
		if ($image->getOutputWidth() > $image->getOutputHeight()) {
			$wm = $size['width'];
			$hm = round($size['width'] / $k);
		} else {
			$wm = round($size['height'] * $k);
			$hm = $size['height'];
		}

		// define new focus point coordinates
		$fx2 = round($image->getFocusPointX1() * $wm / $size['width']);
		$fy2 = round($image->getFocusPointY2() * $hm / $size['height']);

		// crop
		$image->crop($wm, $hm, $image->getFocusPointX1() - $fx2, $image->getFocusPointY2() - $fy2);

		// destination resize
		$image->resize($image->getOutputWidth(), $image->getOutputHeight());

		// sharpen
		$image->sharpen(1);

		// set quality
		$image->quality($this->quality);

		$this->image = $image;
	}

	/**
	 * @param string $destinationPath
	 * @throws \RuntimeException
	 */
	public function save($destinationPath)
	{
		$dir = substr($destinationPath, 0, strrpos($destinationPath, DIRECTORY_SEPARATOR));
		if (!is_writable($dir)) {
			throw new \RuntimeException("Directory $dir is not writable");
		}
		$this->image->save($destinationPath);
	}
}