<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

namespace FcropPhp\Image;


abstract class AImage implements IImage {

	/**
	 * Describe an area on image that contains most important information
	 *
	 * @var array First element of array is X1, second - Y2
	 */
	private $focusPoint;

	/**
	 * Output image width
	 *
	 * @var int
	 */
	private $outputWidth;

	/**
	 * Output image height
	 *
	 * @var int
	 */
	private $outputHeight;

	/**
	 * Save image internal
	 *
	 * @param string $destinationPath Path to cropped image
	 * @return void
	 */
	abstract protected function saveImageInternal($destinationPath);

	/**
	 * Set a center of area on image that contains most important information provided by this image
	 *
	 * @param int $x1
	 * @param int $y2
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return AImage
	 */
	public function setFocusPoint($x1, $y2)
	{
		$x1 = (int)$x1;
		$y2 = (int)$y2;
		if ($x1 < 0 || $y2 < 0) {
			throw new \InvalidArgumentException("$x1 and $y2 attributes for focus point can not be negative");
		}
		$size = $this->getSize();

		if ($x1 > $size['width']) {
			throw new \InvalidArgumentException("$x1 parameter of focus point can not be greater than image width");
		}
		if ($y2 > $size['height']) {
			throw new \InvalidArgumentException("$y2 parameter of focus point can not be greater than image height");
		}
		$this->focusPoint = array($x1, $y2);

		return $this;
	}

	/**
	 * Set image size for output
	 *
	 * @param int $width
	 * @param int $height
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return AImage
	 */
	public function setPreferredSize($width, $height)
	{
		$width = (int)$width;
		$height = (int)$height;

		if ($width < 1 || $height < 1) {
			throw new \InvalidArgumentException("Preferred width and height should be grater than 0");
		}

		$this->outputWidth = $width;
		$this->outputHeight = $height;
	}


	/**
	 * Return x1 point of area of focus point
	 *
	 * @throws \RuntimeException
	 *
	 * @return int
	 */
	public function getFocusPointX1()
	{
		if ($this->focusPoint === null) {
			throw new \RuntimeException('You should specify "focus point" of image');
		}
		return $this->focusPoint[0];
	}

	/**
	 * Return x1 point of area of focus point
	 *
	 * @throws \RuntimeException
	 *
	 * @return int
	 */
	public function getFocusPointY2()
	{
		if ($this->focusPoint === null) {
			throw new \RuntimeException('You should specify "focus point" of image');
		}
		return $this->focusPoint[1];
	}

	/**
	 * Return preferred width of output image
	 *
	 * @throws \RuntimeException
	 * @return int
	 */
	public function getOutputWidth()
	{
		if ($this->outputWidth === null) {
			throw new \RuntimeException('You should specify preferred size of output image');
		}
		return $this->outputWidth;
	}

	/**
	 * Return preferred height of output image
	 *
	 * @throws \RuntimeException
	 * @return int
	 */
	public function getOutputHeight()
	{
		if ($this->outputHeight === null) {
			throw new \RuntimeException('You should specify preferred size of output image');
		}
		return $this->outputHeight;
	}

	/**
	 * @param string $destinationPath
	 *
	 * @throws \RuntimeException
	 */
	public function save($destinationPath)
	{
		$dir = substr($destinationPath, 0, strrpos($destinationPath, DIRECTORY_SEPARATOR));
		if (!is_writable($dir)) {
			throw new \RuntimeException("Directory $dir is not writable");
		}
		$this->saveImageInternal($destinationPath);
	}
}