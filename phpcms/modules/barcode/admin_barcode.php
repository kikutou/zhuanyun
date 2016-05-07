<?php
// Including all required classes
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');
// Including the barcode technology
require_once('class/BCGcode128.barcode.php');

pc_base::load_app_class('admin','admin',0);

class admin_barcode{

	public function init() {
		
		$font = isset($_GET['font']) ? $_GET['font'] : 22;

		// Loading Font
		$font = new BCGFontFile(PHPCMS_PATH.'/phpcms/modules/barcode/font/OratorStd.otf', $font);

		// Don't forget to sanitize user inputs
		$text = isset($_GET['data']) ? $_GET['data'] : '';
		if(empty($text))
		{
			exit;
		}
		// The arguments are R, G, B for color.
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);

		$drawException = null;
		try {
			$code = new BCGcode128();
			$code->setScale(3); // Resolution
			$code->setThickness(18); // Thickness
			$code->setForegroundColor($color_black); // Color of bars
			$code->setBackgroundColor($color_white); // Color of spaces
			$code->setFont($font); // Font (or 0)
			$code->parse($text); // Text
		} catch(Exception $exception) {
			$drawException = $exception;
		}

		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing = new BCGDrawing('', $color_white);
		if($drawException) {
			$drawing->drawException($drawException);
		} else {
			$drawing->setBarcode($code);
			$drawing->draw();
		}

		// Header that says it is an image (remove it if you save the barcode to a file)
		header('Content-Type: image/png');
		header('Content-Disposition: inline; filename="barcode.png"');

		// Draw (or save) the image into PNG format.
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

	}

}
?>