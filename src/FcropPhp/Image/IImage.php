<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

namespace FcropPhp\Image;


interface IImage {

	/**
	 * Set image size for output
	 *
	 * @param int $width
	 * @param int $height
	 *
	 * @throws \InvalidArgumentException
	 * @return AImage
	 */
	public function setPreferredSize($width, $height);

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
	public function setFocusPoint($x1, $y2);

	/**
	 * Create image via \Imagick and return self
	 * @param string $filePath Path to image
	 * @return AImage
	 */
	public function load($filePath);

	/**
	 * @param int $width The width of the crop
	 * @param int $height The height of the crop
	 * @param int $x The X coordinate of the cropped region's top left corner
	 * @param int $y The Y coordinate of the cropped region's top left corner
	 *
	 * @throws \RuntimeException
	 *
	 * @return AImage
	 */
	public function crop($width, $height, $x, $y);

	/**
	 * @param int $width
	 * @param int $height
	 * @return AImage
	 */
	public function resize($width, $height);

	/**
	 * Sharpens an image
	 * @param int $amount It is sigma
	 *
	 * @throws \RuntimeException
	 *
	 * @return AImage
	 */
	public function sharpen($amount);

	/**
	 * Set image compression quality
	 *
	 * @param $quality
	 *
	 * @throws \RuntimeException
	 *
	 * @return AImage
	 */
	public function quality($quality);

	/**
	 * Return origin image size
	 *
	 * @return array the size as an array with the
	 * keys "width" and "height".
	 */
	public function getSize();

	/**
	 * Save cropped image
	 *
	 * @param string $destinationPath
	 *
	 * @throws \RuntimeException
	 */
	public function save($destinationPath);
} 