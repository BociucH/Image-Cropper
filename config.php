<?php

/*
|---------------------------------------------------------
| Directory where all the images will be stored
|---------------------------------------------------------
*/
define('IMG_PATH', 'img/');

return [
	
	/*
	|---------------------------------------------------------
	| Name of the file-type input
	|---------------------------------------------------------
	|
	| Example:
	| <input type="file" name="image">
	*/
	'formInputName' => 'image',
	
	/*
	|---------------------------------------------------------
	| Directory for temporary images
	|---------------------------------------------------------
	|
	| It's used by the script for converting output images
	*/
	'tmpDir' => IMG_PATH . 'tmp/',

	/*
	|---------------------------------------------------------
	| Directory for output images
	|---------------------------------------------------------
	*/
	'croppedDir' => IMG_PATH . 'cropped/',

	/*
	|---------------------------------------------------------
	| Allowed image formats
	|---------------------------------------------------------
	|
	| List of types you can use:
	|  - jpg
	|  - jpeg
	*/
	'allowedFormats' => ['jpg'],

	/*
	|---------------------------------------------------------
	| Width of the outputed image
	|---------------------------------------------------------
	|
	| Note: Height will be counted automatically with an aspect ratio
	*/
	'resizeWidth' => 800

];