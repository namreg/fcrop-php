<?php
/**
 * @author namreg <iggerman@yandex.com>
 */

namespace FcropPhp\Image\Driver;


use FcropPhp\Image\AImage;

class ImageMagick extends AImage {

	/**
	 * Imagick instance of image
	 *
	 * @var \Imagick
	 */
	private $image;

	/**
	 * @inheritdoc
	 */
	public function load($filePath)
	{
		$this->image = new \Imagick($filePath);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function crop($width, $height, $x, $y)
	{
		if ($this->image === null) {
			throw new \RuntimeException('At first you should load image');
		}
		if (!$this->image->cropimage($width, $height, $x, $y)) {
			throw new \RuntimeException('Can not crop image');
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function resize($columns, $rows)
	{
		if ($this->image === null) {
			throw new \RuntimeException('At first you should load image');
		}
		if (!$this->image->scaleimage($columns, $rows)) {
			throw new \RuntimeException('Can not resize image');
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function sharpen($amount)
	{
		if ($this->image === null) {
			throw new \RuntimeException('At first you should load image');
		}
		//IM not support $amount under 5 (0.15)
		$amount = ($amount < 5) ? 5 : $amount;

		// Amount should be in the range of 0.0 to 3.0
		$amount = ($amount * 3.0) / 100;
		if (!$this->image->sharpenimage(0, $amount)) {
			throw new \RuntimeException('Can not make sharpen image');
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function quality($quality)
	{
		if (!$this->image->setimagecompressionquality($quality)) {
			throw new \RuntimeException('Cant not set quality');
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	protected function saveImageInternal($destinationPath)
	{
		if (!$this->image->writeimage($destinationPath)) {
			throw new \RuntimeException('Can not save file');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getSize()
	{
		return $this->image->getImageGeometry();
	}
}