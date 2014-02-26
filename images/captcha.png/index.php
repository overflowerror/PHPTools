<?php
	@session_start();
	
	$letters = "ABCDEFGHIJKLMNOPQRSTUVWgqrtimnaeh";
	$length = 8;
	$fontsize = 30;
	
	$offset = 10;
	
	$fieldWidth = 50;
	$margin = $offset + 5;
	
	$imagesize = array();
	$imagesize['x'] = $fieldWidth * $length + 2 * $margin;
	$imagesize['y'] = 80;
	
	$fonts = array();
	// add your fonts here
	$fonts[] = "../../fonts/averia.ttf";
	$fonts[] = "../../fonts/bbc.ttf";
	$fonts[] = "../../fonts/bybsy.ttf";
	$fonts[] = "../../fonts/gtw.ttf";
	$fonts[] = "../../fonts/newscycle.ttf";
	
	$numberOfBackgroundLines = 50;
	$thicknesOfBackgroundLines = 2;
	$numberOfFrontLines = 2;
	$thicknesOfFrontLines = 3;
	
	$captcha = array();
	for ($i = 0; $i < $length; $i++) {
		$random = rand(0, strlen($letters) - 1);
		$captcha[$i] = substr($letters, $random, 1);
	}
	
	$rotations = array();
	for ($i = 0; $i < $length; $i++) {
		$random = rand(- $offset, $offset);
		$rotations[$i] = $random;
	}
	
	$positions = array();
	for ($i = 0; $i < $length; $i++) {
		$random = rand(- $offset, $offset);
		$positions[$i] = array();
		$positions[$i]['x'] = $random;
		$random = rand(- $offset, $offset);
		$positions[$i]['y'] = $random;
	}
	
	$lines = array();
	for ($i = 0; $i < $numberOfBackgroundLines; $i++) {
		$lines[$i] = array();
		$lines[$i][0] = rand(0, $imagesize['x']);
		$lines[$i][1] = rand(0, $imagesize['y']);
		$lines[$i][2] = rand(0, $imagesize['x']);
		$lines[$i][3] = rand(0, $imagesize['y']);
		$lines[$i][4] = $thicknesOfBackgroundLines;
	}
	for ($i = $numberOfBackgroundLines; $i < $numberOfBackgroundLines + $numberOfFrontLines; $i++) {
		$lines[$i] = array();
		$lines[$i][0] = rand(0, $imagesize['x']);
		$lines[$i][1] = rand(0, $imagesize['y']);
		$lines[$i][2] = rand(0, $imagesize['x']);
		$lines[$i][3] = rand(0, $imagesize['y']);
		$lines[$i][4] = $thicknesOfFrontLines;
	}
	
	$font = $_SERVER['DOCUMENT_ROOT'] . $fonts[rand(0, count($fonts) - 1)];
	
	$image = imagecreatetruecolor($imagesize['x'], $imagesize['y']);
	
	for ($i = 0; $i < $numberOfBackgroundLines; $i++) {
		imagesetthickness($image, $lines[$i][4]);
		$random = rand(0, 128);
		$color = imagecolorallocate($image, $random, 127 - $random, $random);
		imageline($image, $lines[$i][0], $lines[$i][1], $lines[$i][2], $lines[$i][3], $color);
	}
	
	$random = rand(0, 255);
	$fontColor = imagecolorallocate($image, $random, 255, 255 - $random);
	
	for ($i = 0; $i < $length; $i++) {
		imagettftext($image, $fontsize, $rotations[$i], $offset + $i * $fieldWidth + $positions[$i]['x'], $imagesize['y'] - $fontsize / 2 + $positions[$i]['y'], $fontColor, $font, $captcha[$i]);
	}
	
	for ($i = $numberOfBackgroundLines; $i < $numberOfBackgroundLines + $numberOfFrontLines; $i++) {
		imagesetthickness($image, $lines[$i][4]);
		$random = rand(0, 128);
		$color = imagecolorallocate($image, $random, 127 - $random, $random);
		imageline($image, $lines[$i][0], $lines[$i][1], $lines[$i][2], $lines[$i][3], $color);
	}
	
	// Note: only one captcha code at a time can be used
	$_SESSION['captcha'] = array();
	$_SESSION['captcha']['code'] = $captcha;
	$_SESSION['captcha']['time'] = time();
	$_SESSION['captcha']['used'] = false;
	
	header( "Content-type: image/png" );
	imagepng($image);
	imagedestroy($image);
?>
