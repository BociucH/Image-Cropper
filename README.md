# Image-Cropper
A tiny script for resizing images to the desired width. The height is counted automatically with an aspect ratio.

## Usage
Invoke the script by a HTML form, example:
```html
<form action="cropper.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="image">
  <input type="submit" value="Crop">
</form>	
```

The configuration file is:
```bash
config.php
```

### Options
<dl>
<dt>IMG_PATH</dt>
<dd>defines the directory where all the images will be stored</dd>
<dt>formInputName</dt>
<dd>name of the input (type: file) in HTML form</dd>
<dt>tmpDir</dt>
<dd>it's used by the script for converting output images</dd>
<dt>croppedDir</dt>
<dd>directory for output images</dd>
<dt>allowedFormats</dt>
<dd>allowed image formats (for now, only *.jpg available)</dd>
<dt>resizeWidth</dt>
<dd>width of the outputed image</dd>
</dl>
