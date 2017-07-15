<?php declare(strict_types=1);

# Require configuration file
$config = (require 'config.php');

# Example start
$imageCropper = new ImageCropper($config, $_FILES[$config['formInputName']]);
echo $imageCropper->init();

/*
|-----------------------------------------------------
| PHP Cropper boot class
|-----------------------------------------------------
*/
class ImageCropper {

	/**
	 * Config with all the essential settings
	 * @var array
	 */
	private $config;
	
	/**
	 * File data
	 * @var array
	 */
	private $file;

	/**
	 * File extension
	 * @var string
	 */
	private $fileExt;

	/**
	 * Original width of the image 
	 * @var int
	 */
	private $origWidth;

	/**
	 * Original height of the image
	 * @var int
	 */
	private $origHeight;

	/**
	 * Constructor
	 */
	public function __construct(Array $config, Array $file) {
		$this->config = $config;
		$this->file = $file;
	}

	/**
	 * Initialize
	 * Note: This method has to be invoked to start the script
	 * @return string 	New-generated image
	 */
	public function init(): string {
		# Set file extenstion
		$this->setFileExt($this->file['name']);

		# Create required directories
		$this->createDirectories();

		# Upload a temporary file (it will be used for cropping later on)
		$tmpName = $this->uploadFile($this->file['tmp_name'], $this->config['tmpDir']);
		
		# Crop and upload the image
		return $this->createCopy($tmpName) . '.' . $this->fileExt;
	}

	/**
	 * Set file extension
	 * @param string $filename Sent file name (eg. image.jpg)
	 * @return void
	 */
	private function setFileExt(string $filename): void {
		$extArray = explode('.', $filename);
		$this->fileExt = end($extArray);
	}

	/**
	 * Create required directories
	 * @return void
	 */
	private function createDirectories(): void {
		if (!is_dir($this->config['tmpDir'])) {
			mkdir($this->config['tmpDir'], 0755, true);
		}

		if (!is_dir($this->config['croppedDir'])) {
			mkdir($this->config['croppedDir'], 0755, true);
		}
	}

	/**
	 * Upload a file with a brand new-generated name
	 * @param  string $tmpName  Temporary file name
	 * @param  string $dir 		Destination directory
	 * @return string           New-generated name
	 */
	private function uploadFile(string $tmpName, string $dir): string {
		# Check image format
		if (!in_array($this->fileExt, $this->config['allowedFormats'])) {
			throw new Exception('Improper image type');
		}

		$genName = $this->generateName();

		move_uploaded_file($tmpName, $dir . $genName . '.' . $this->fileExt);

		return $genName; 
	}

	/**
	 * Generate a random name
	 * @return string Random name
	 */
	private function generateName(): string {
		$genName = bin2hex(openssl_random_pseudo_bytes(12)) . md5($this->file['name']);

		return $genName;
	}

	/**
	 * Create a copy of the temporary image
	 * Invoke a resize() method which creates a new resized image and uploads it to the server
	 * @param  string $tmpName Temporary image name
	 * @return string 		   New-generated image
	 */
	private function createCopy(string $tmpName): string {
		$filename = $this->config['tmpDir'] . $tmpName . '.' . 'jpg';
		
		switch ($this->fileExt) {
			case 'jpg':
				$createdImg = imagecreatefromjpeg($filename);
				break;
		}

		$this->origWidth = imagesx($createdImg);
		$this->origHeight = imagesy($createdImg);

		return $this->resize($createdImg);
	}

	/**
	 * Create a new resized image and upload it to the server
	 * @param  resource $createdImg Image copy
	 * @return string 	New-generated image
	 */
	private function resize($createdImg): string {
		$resizeWidth = $this->config['resizeWidth'];
		$resizeHeight = $this->heightRatio($resizeWidth);

		$newImg = imagecreatetruecolor((int) $resizeWidth, (int) $resizeHeight);
		imagecopyresampled($newImg, $createdImg, 0, 0, 0, 0, (int) $resizeWidth, (int) $resizeHeight, $this->origWidth, $this->origHeight);

		$genName = $this->generateName();
		imagejpeg($newImg, $this->config['croppedDir'] . $genName . '.' . $this->fileExt);

		return $genName;
	}

	/**
	 * Count the height using an aspect ratio (proportional)
	 * @param  int $width New Width
	 * @return int 		  Counted ratio
	 */
	private function heightRatio(int $width): int {
		return intval(floor(($this->origHeight / $this->origWidth) * $width));
	}

}
