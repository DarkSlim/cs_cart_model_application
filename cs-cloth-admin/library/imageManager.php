<?php
/*
 * image manipulation class
 * @verion 1.0.0.0
 * @author Pazarkoski Riste
 * @license GNU Public License
 */

class ImageManager {
      /*       * *********************************************************************************************** 
       *  @method void  class construct method
       * ************************************************************************************************ */
      public function __construct() {
            
      }
      /*       * *********************************************************************************************** 
       *  @method mixed resizes image
       *  @param $image_path the image path to the image that will be resized
       *  @param $new_width new width of the resized image
       *  @param $new_height new height of the resized image
       *  @param $crop should the image be cropped default is false
       *  @param $quality quality of the resized image, default 100% this only applies for jpg and png images
       *  @param $destination if set, resized image will be saved to that location. Otherwise the existing one will be overwriten
       * ************************************************************************************************ */
      public static function resizeImage($image_path, $new_width, $new_height, $crop = false, $destination = "") {
            //Get image dimensions
            if (!list($w, $h) = getimagesize($image_path))
                  return "Unsupported image type!";

            //Create image resource
            $type = strtolower(substr(strrchr($image_path, "."), 1));
            if ($type == 'jpeg')
                  $type = 'jpg';
            switch ($type) {
                  case 'bmp': $img = imagecreatefromwbmp($image_path);
                        break;
                  case 'gif': $img = imagecreatefromgif($image_path);
                        break;
                  case 'jpg': $img = imagecreatefromjpeg($image_path);
                        break;
                  case 'png': $img = imagecreatefrompng($image_path);
                        break;
                  default : return "Unsupported image type!";
            }

            // Crop resize
            if ($crop) {
                  if ($w < $new_width) {
                        $new_width = $w;
                  }
                  if ($h < $new_height) {
                        $new_height = $h;
                  }

                  $ratio = max($new_width / $w, $new_height / $h);
                  $h = $new_height / $ratio;
                  $x = ($w - $new_width / $ratio) / 2;
                  $w = $new_width / $ratio;
            }
            //Proportional resize
            else {
                  if ($w < $new_width) {
                        $new_width = $w;
                  }
                  if ($h < $new_height) {
                        $new_height = $h;
                  }

                  $ratio = min($new_width / $w, $new_height / $h);
                  $new_width = $w * $ratio;
                  $new_height = $h * $ratio;
                  $x = 0;
            }

            $new = imagecreatetruecolor($new_width, $new_height);

            // preserve transparency
            if ($type == "gif" || $type == "png") {
                  imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
                  imagealphablending($new, false);
                  imagesavealpha($new, true);
            }

            imagecopyresampled($new, $img, 0, 0, $x, 0, $new_width, $new_height, $w, $h);

            $destination = empty($destination) ? $image_path : $destination;
            switch ($type) {
                  case 'bmp': imagewbmp($new, $destination);
                        break;
                  case 'gif': imagegif($new, $destination);
                        break;
                  case 'jpg': imagejpeg($new, $destination, 90);
                        break;
                  case 'png': imagepng($new, $destination, 9);
                        break;
            }
            return $destination;
      }
      /*       * *********************************************************************************************** 
       *  @method mixed copies image
       *  @param string $src_image source image that will be copied
       *  @param string $dest_image new image path
       * ************************************************************************************************ */
      public static function copyImage($src_image, $dest_image_path) {
            try {
                  //Check if $src_image exists
                  if (!file_exists($src_image)) {
                        throw new Exception("<strong>" . $src_image . "</strong> does not exist");
                  }
                  //Get src image  type
                  $image_ext = pathinfo($src_image, 4);
                  //Get src image width and height
                  list($img_width, $img_height) = getimagesize($src_image);
                  //Create the copy image wrapper
                  $dest = imagecreatetruecolor($img_width, $img_height);
                  //Check image type and create image
                  // Supported image types are jpg, png, gif
                  switch ($image_ext) {
                        case "jpg" :
                              $src = imagecreatefromjpeg($src_image);
                              break;
                        case "png":
                              $src = imagecreatefrompng($src_image);
                              break;
                        case "gif":
                              $src = imagecreatefromgif($src_image);
                              break;
                        case "bmp":
                              $src = imagecreatefromwbmp($src_image);
                              break;
                        default :
                              throw new Exception("<strong>." . $image_ext . "</strong> - Unsupported image extension");
                              break;
                  }
                  // preserve transparency
                  if ($image_ext == "png" || $image_ext == "gif") {
                        imagecolortransparent($dest, imagecolorallocatealpha($dest, 0, 0, 0, 127));
                        imagealphablending($dest, false);
                        imagesavealpha($dest, true);
                  }
                  //Create the copy
                  imagecopyresampled($dest, $src, 0, 0, 0, 0, $img_width, $img_height, $img_width, $img_height);
                  //Save the copy image
                  switch ($image_ext) {
                        case "jpg" :
                              imagejpeg($dest, $dest_image_path, 95);
                              break;
                        case "png":
                              imagepng($dest, $dest_image_path, 9);
                              break;
                        case "gif":
                              imagegif($dest, $dest_image_path);
                              break;
                        case "bmp":
                              imagewbmp($dest, $dest_image_path);
                              break;
                        default :
                              throw new Exception("<strong>." . $image_ext . "</strong> - Unsupported image extension");
                              break;
                  }
                  //destroy the images
                  imagedestroy($dest);
                  imagedestroy($src);

                  return true;
            } catch (Exception $e) {
                  echo $e->getMessage();
            }
      }
      /*       * *********************************************************************************************** 
       *  @method mixed ads watermark image
       *  @param string $image the image that will be resized
       *  @param string $watermark_image image to be used as watermark
       *  @param $xpos X position of the watermark
       *  @param $ypos Y position of the watermark
       *  @param $save_path the destination image path
       *  @param $position posible values bottom-left, bottom-right, top-left, top-right, center
       * ************************************************************************************************ */
      public static function addWatermarkImage($src_image, $watermark_image, $xpos, $ypos, $save_path, $position = "bottom-right") {
            try {
                  //Check if both images are valid url locations
                  if (!file_exists($src_image)) {
                        throw new Exception("<strong>" . $src_image . "</strong> does not exist");
                  }
                  if (!file_exists($watermark_image)) {
                        throw new Exception("<strong>" . $watermark_image . "</strong> does not exist");
                  }
                  //Get src image  type
                  $src_image_ext = pathinfo($src_image, 4);
                  //Get watermark image type
                  $watermark_image_ext = pathinfo($watermark_image, 4);
                  //Get src image width and height
                  list($img_width, $img_height) = getimagesize($src_image);
                  //Get watermark image dimensions
                  list($watermark_width, $watermark_height) = getimagesize($watermark_image);

                  //Create the base image
                  $base_image = imagecreatetruecolor($img_width, $img_height);

                  imagealphablending($base_image, false);
                  $col = imagecolorallocatealpha($base_image, 255, 255, 255, 127);
                  imagefilledrectangle($base_image, 0, 0, $img_width, $img_height, $col);
                  imagealphablending($base_image, true);
                  imagesavealpha($base_image, true);

                  //Check image type and create image
                  // Supported image types are jpg, png, gif
                  //create src image
                  switch ($src_image_ext) {
                        case "jpg" :
                              $dest = imagecreatefromjpeg($src_image);
                              break;
                        case "png":
                              $dest = imagecreatefrompng($src_image);
                              break;
                        case "gif":
                              $dest = imagecreatefromgif($src_image);
                              break;
                        case "bmp":
                              $src = imagecreatefromwbmp($src_image);
                              break;
                        default :
                              throw new Exception("<strong>." . $src_image_ext . "</strong> - Unsupported image extension");
                              break;
                  }
                  //create watermark image
                  switch ($watermark_image_ext) {
                        case "jpg" :
                              $watermark = imagecreatefromjpeg($watermark_image);
                              break;
                        case "png":
                              $watermark = imagecreatefrompng($watermark_image);
                              break;
                        case "gif":
                              $watermark = imagecreatefromgif($watermark_image);
                              break;
                        case "bmp":
                              $src = imagecreatefromwbmp($watermark_image);
                              break;
                        default :
                              throw new Exception("<strong>." . $watermark_image_ext . "</strong> - Unsupported image extension");
                              break;
                  }
                  // preserve transparency of the watermark
                  if ($watermark_image_ext == "png" || $watermark_image_ext == "gif") {
                        imagecolortransparent($watermark, imagecolorallocatealpha($watermark, 0, 0, 0, 127));
                        imagealphablending($watermark, false);
                        imagesavealpha($watermark, true);
                  }
                  //Set position of the watermark
                  switch ($position) {
                        case "top-left":
                              $xpos = 30;
                              $ypos = 30;
                              break;
                        case "top-right":
                              $xpos = $img_width - $watermark_width - 30;
                              $ypos = 30;
                              break;
                        case "bottom-left":
                              $xpos = 30;
                              $ypos = $img_height - $watermark_height - 30;
                              break;
                        case "bottom-right":
                              $xpos = $img_width - $watermark_width - 30;
                              $ypos = $img_height - $watermark_height - 30;
                              break;
                        case "center":
                              $xpos = ($img_width / 2) - ($watermark_width / 2);
                              $ypos = ($img_height / 2) - ($watermark_height / 2);
                              break;
                  }
                  // merge images
                  imagecopy($base_image, $dest, 0, 0, 0, 0, $img_width, $img_height);
                  imagecopy($base_image, $watermark, $xpos, $ypos, 0, 0, $watermark_width, $watermark_height);

                  //Save the new image with watermark
                  imagepng($base_image, $save_path, 9);
                  //destroy the images
                  imagedestroy($watermark);
                  imagedestroy($dest);
                  imagedestroy($base_image);

                  return true;
            } catch (Exception $e) {
                  echo $e->getMessage();
            }
      }
}

?>