<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-07 13:41:42
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-05 12:29:38
 */

class Image{
	function ImageToJPG($originalFile, $outputFile, $type, $quality=100){
		//Se obtiene la extension del archivo
		if($type == 'jpeg') $type = 'jpg';
		//Se convierte a una imagen manejable
		switch($type){
		    case 'bmp': $img = imagecreatefromwbmp($originalFile); break;
		    case 'gif': $img = imagecreatefromgif($originalFile); break;
		    case 'jpg': $img = imagecreatefromjpeg($originalFile); break;
		    case 'png': $img = imagecreatefrompng($originalFile); break;
		    case 'webp': $img = imagecreatefromwebp($originalFile); break;
		    default : return "Unsupported picture type!";
		}
		//Se crea el archivo temporal con el jpg
	    imagejpeg($img, $outputFile, $quality);
	    //Se destruye los datos de la imagen
	    imagedestroy($img);
	}
	function reduce($file, $destino, $ancho = 800, $alto = 800, $quality=75, $extension=null){
		//Obytenemos la extension si no se especifca antes
		if(!$extension)
		{
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$extension=strtolower($extension);
		}
		//Nombre Temporal
		$name=strtotime("now");
		//Destino del archivo temporal
		$nombre_archivo_jpg=$_SERVER["DOCUMENT_ROOT"]."/img/$name.jpg";

		//Se obtiene la imagen temporal
		$this->ImageToJPG($file, $nombre_archivo_jpg ,$extension );

		//Se obtienen dimensiones
		list($ancho_orig, $alto_orig) = getimagesize($nombre_archivo_jpg);

		$ratio_orig = $ancho_orig/$alto_orig;

		if ($ancho/$alto > $ratio_orig) {
		   $ancho = $alto*$ratio_orig;
		} else {
		   $alto = $ancho/$ratio_orig;
		}

		//Se obtiene la imagen
		$image_p = imagecreatetruecolor($ancho, $alto);

		//Se crea la imagen a traves del temporal
		$image = imagecreatefromjpeg($nombre_archivo_jpg);

		//Se crea la imagen
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $ancho, $alto, $ancho_orig, $alto_orig);

		//Borramos el archivo temporal
		unlink($nombre_archivo_jpg);

		//Se guarda la imagen en el lugar indicado
		imagejpeg($image_p, $destino, 50);

		//Destruimos los datos de la imagen antigua
		imagedestroy($image_p);
	}
}