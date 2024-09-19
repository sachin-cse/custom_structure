<?php
class FileUpload {
    
    private $_allowed_ext = array();
    public function __construct(){
        $this->_allowed_ext = array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf');
    }
    
    public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
    {
        list($sm_width, $sm_height) = getimagesize($image); //Get the size of image
        if($sm_width <= $width && $sm_height<= $height) {
            copy($image, $thumb_image_name);
        }
        else {
            if($width>$sm_width) {
                $start_width    = 0;
                $ratio          = $sm_width/$width;             // ratio calculation  to scale the  Thumbnail image

                $width          = $sm_width;
                $height         = $height * $ratio;
            }
            elseif($height>$sm_height) {
                $start_height   = 0;
                $ratio          = $sm_height/$height;           // ratio calculation  to scale the  Thumbnail image

                $height         = $sm_height;
                $width          = $width * $ratio;
            }

            $newImageWidth      = ceil($width * $scale);
            $newImageHeight     = ceil($height * $scale);

            $newImage           = imagecreatetruecolor($newImageWidth,$newImageHeight);

            $extension_array    = pathinfo($image);	
            $extension          = strtolower($extension_array['extension']);

            if($extension=='jpeg' || $extension=='jpg')
                $source = imagecreatefromjpeg( $image );	    //for JPEG or JPG image file
            elseif($extension=='gif')
                $source = imagecreatefromgif($image);           //for GIF image file
            elseif($extension=='png') {
                $source = imagecreatefrompng($image);
                imagesavealpha($source, true);
            }//for PNG image file
            elseif($extension=='bmp')
                $source = imagecreatefromwbmp( $image );        //for BMP image file	

            if($extension=='png' || $extension=='gif') {
                imagealphablending($newImage, false);
                $color = imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                imagefill($newImage, 0, 0, $color);
                imagesavealpha($newImage, true);
            }

            imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
            if($extension=='jpeg' || $extension=='jpg')
                imagejpeg($newImage,$thumb_image_name,100);     //for JPEG or JPG image file
            elseif($extension=='bmp')
                imagewbmp($newImage,$thumb_image_name,100);     //for BMP image file
            elseif($extension=='png')
                imagepng($newImage, $thumb_image_name);         //for PNG image file
            
            return $thumb_image_name;
        }
    }
    
    public function createResizeForThumbnail($source_file, $destination_path, $image_name, $dest_width, $dest_height)
    {
        $destination_file       = $destination_path.$image_name;
        
        //coping file to destination path ------------------START
        copy($source_file, $destination_file);
        //-----------------------------------------------------END
        
        $image_attribs          = getimagesize($destination_file);  //Get the size of
        $src_width              = $image_attribs[0];
        $src_height             = $image_attribs[1];
        
        if($src_width <= $dest_width && $src_height<= $dest_height) {
            // do nothing---------------------------------
        }
        else {
            $extension_array    = pathinfo($destination_file);
            $extension          = strtolower($extension_array['extension']);

            if($extension == 'jpeg' || $extension == 'jpg')
                $src = imagecreatefromjpeg( $destination_file );    //for JPEG or JPG image file
            elseif($extension=='gif')
                $src = imagecreatefromgif( $destination_file );     //for GIF image file	
            elseif($extension=='png') {
                $src = imagecreatefrompng( $destination_file );
                imagesavealpha($src, true);
            }//for PNG image file	
            elseif($extension=='bmp')
                $src = imagecreatefromwbmp( $destination_file );    //for BMP image file	

            if($src_width > $src_height) {
                $ratio          = $dest_width/$src_width;           // ratio calculation  to scale the  Thumbnail image
                $th_width       = $dest_width;
                $th_height      = $src_height * $ratio;
                if($th_height<=$dest_height) {
                    $ratio      = $dest_height/$src_height;         // ratio calculation  to scale the  Thumbnail image
                    $th_width   = $src_width * $ratio;
                    $th_height  = $dest_height;
                }
            }
            else {
                $ratio          = $dest_height/$src_height;         // ratio calculation  to scale the  Thumbnail image
                $th_width       = $src_width * $ratio;
                $th_height      = $dest_height;
                if($th_width<=$dest_width) {
                    $ratio      = $dest_width/$src_width;           // ratio calculation  to scale the  Thumbnail image
                    $th_width   = $dest_width;
                    $th_height  = $src_height * $ratio;
                }
            }
            /* create and output the destination image */

            /*echo $th_width.'<-w::h->'.$th_height;*/
            
            $dest = imagecreatetruecolor($th_width, $th_height);    //Create a new true color image
            if($extension=='png' || $extension=='gif') {
                imagealphablending($dest, false);
                $color = imagecolortransparent($dest, imagecolorallocatealpha($dest, 0, 0, 0, 127));
                imagefill($dest, 0, 0, $color);
                imagesavealpha($dest, true);
            }
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $th_width, $th_height, $src_width, $src_height);    //Copy and resize part of an image

            if($extension=='jpeg' || $extension=='jpg')
                imagejpeg($dest,$destination_file,100);             //for JPEG or JPG image file
            elseif($extension=='bmp')
                imagewbmp($dest,$destination_file,100);             //for BMP image file
            elseif($extension=='png')
                imagepng($dest, $destination_file);                 //for PNG image file
            
            //RESIZING file to destination path ------------------END
            return $image_name;
        }
    }
    
    public function create_resize($source_file, $destination_path, $image_name, $dest_width, $dest_height)
    {
        $destination_file = $destination_path.$image_name;
        //coping file to destination path ------------------START
        copy($source_file,$destination_file);
        //-----------------------------------------------------END	

        $image_attribs  = getimagesize($destination_file);          //Get the size of image
        $src_width      = $image_attribs[0];
        $src_height     = $image_attribs[1];
        if($src_width <= $dest_width && $src_height<= $dest_height) {
            // do nothing---------------------------------
        }
        else {
            $extension_array    = pathinfo($destination_file);	
            $extension          = strtolower($extension_array['extension']);
            
            if($extension=='jpg' || $extension=='jpeg')
                $src = imagecreatefromjpeg( $destination_file );    //for JPEG or JPG image file	
            elseif($extension=='gif')
                $src = imagecreatefromgif( $destination_file );     //for GIF image file	
            elseif($extension=='png') {
                $src = imagecreatefrompng( $destination_file ); 
                imagesavealpha($src, true); //saving transparency
            }//for PNG image file	
            elseif($extension=='bmp')
                $src = imagecreatefromwbmp( $destination_file );    //for BMP image file	

            if($src_width > $dest_width) {
                $ratio          = $dest_width/$src_width;           // ratio calculation  to scale the  Thumbnail image
                $th_width       = $dest_width;
                $th_height      = $src_height * $ratio;
                if($th_height>$dest_height) {
                    $ratio      = $dest_height/$src_height;         // ratio calculation  to scale the  Thumbnail image
                    $th_width   = $src_width * $ratio;
                    $th_height  = $dest_height;
                }
            }
            elseif($src_height > $dest_height) {
                $ratio          = $dest_height/$src_height;         // ratio calculation  to scale the  Thumbnail image
                $th_height      = $dest_height;
                $th_width       = $src_width * $ratio;
                if($th_width>$dest_width) {
                    $ratio      = $dest_width/$src_width;           // ratio calculation  to scale the  Thumbnail image
                    $th_height  = $src_height * $ratio;
                    $th_width   = $dest_width;
                }
            }
            else {
                $ratio          = $dest_height/$src_height;         // ratio calculation  to scale the  Thumbnail image
                $th_width       = $src_width * $ratio;
                $th_height      = $dest_height;
                if($th_width>$dest_width) {
                    $ratio      = $dest_width/$src_width;           // ratio calculation  to scale the  Thumbnail image
                    $th_width   = $dest_width;
                    $th_height  = $src_height * $ratio;
                }
            }	
            /* create and output the destination image */
            $dest = imagecreatetruecolor($th_width,$th_height);     //Create a new true color image
            if($extension=='png' || $extension=='gif') {
                imagealphablending($dest, false);
                $color = imagecolortransparent($dest, imagecolorallocatealpha($newPng, 0, 0, 0, 127));
                imagefill($dest, 0, 0, $color);
                imagesavealpha($dest, true);
                imagecopyresampled($dest, $src, 0, 0, 0, 0, $th_width,$th_height, $src_width, $src_height);	//Copy and resize part of an image			
                //header("Content-type: image/png");
                imagepng($newPng,$newImageName);
            }
            else {
                imagecopyresampled($dest, $src, 0, 0, 0, 0, $th_width,$th_height, $src_width, $src_height);	//Copy and resize part of an image

                if($extension=='jpeg' || $extension=='jpg')
                    imagejpeg($dest,$destination_file,100);         //for JPEG or JPG image file
                elseif($extension=='bmp')
                    imagewbmp($dest,$destination_file,100);         //for BMP image file
                //RESIZING file to destination path ------------------END
            }
            return $image_name;
        }
    }
    
    public function moveUploadedFile($source, $destination, $imagick = true)
    {
        if(is_array($source)){
            $extension_array = pathinfo($source['name']);
            $ext = strtolower($extension_array['extension']);
        }
        else
            $ext = end(explode('.', $source));

        if(in_array($ext, $this->_allowed_ext)) {
            if(is_array($source))
                move_uploaded_file($source['tmp_name'], $destination);
            else
                copy($source, $destination);
                
            if($imagick == true) {
                $image = new Imagick($destination);
                $this->autoRotateImage($image); 
                // - Do other stuff to the image here - 
                $image->writeImage($destination); 
            }
            
            return true;
        }
        else
            return false;
    }
    
    function watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile, $targetLocation)
    { 
        list($width, $height)   = getimagesize($SourceFile);
        $image_p                = imagecreatetruecolor($width, $height);
        $image                  = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
        $black                  = imagecolorallocate($image_p, 255, 255, 255);

        $font                   = $targetLocation.'lato-bold-webfont.ttf';
        $font_size              = '20px';

        $x = floor($width / 2) - 71; // text width / 2 = 142 / 2 = 71
        $y = floor($height / 2);

        imagettftext($image_p, $font_size, 0, $x, $y, $black, $font, $WaterMarkText);
        if ($DestinationFile<>'') {
            imagejpeg ($image_p, $DestinationFile, 100);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, 100);
        };
        imagedestroy($image);
        imagedestroy($image_p);
    }
    
    public function uploadImage($source, $targetLocation, $fileName, $TWH, $LWH, $option='all', $waterMark='')
    {
        // $option = upload, thumbnail, resize, all
        if(is_array($source)){
            $extension_lg_array = pathinfo($source['name']);
            $extension = strtolower($extension_lg_array['extension']);
        }
        else
            $extension = strtolower(end(explode('.',$source)));
        
        $fileName           .= '.'.$extension;
        $target_file_normal  = $targetLocation."/normal/".$fileName;
        
        if($this->moveUploadedFile($source, $target_file_normal)) {
            
            if($waterMark)
                $this->watermarkImage ($target_file_normal, $waterMark, $target_file_normal, $targetLocation);
            
            if($option=='thumbnail' || $option=='resize' || $option=='all') {
                $targetfilepath_small   = $targetLocation."/small/";
                $targetfilepath_thumb   = $targetLocation."/thumb/";
                $targetfilepath_large   = $targetLocation."/large/";

                /*if($extension!='jpg' && $extension!='jpeg') {
                    if($option=='thumbnail' || $option=='all') {
                        $destination_t= $targetfilepath_thumb.$fileName;
                        copy($target_file_normal, $destination_t);
                    }
                    if($option=='resize' || $option=='all') {
                        $destination_l= $targetfilepath_large.$fileName;
                        copy($target_file_normal, $destination_l);
                    }
                }
                else*/ {
                    //Getting Actual Image Height - Width
                    list($ac_width, $ac_height) = getimagesize($target_file_normal);
                    
                    if($option=='thumbnail' || $option=='all') {
                        // Resizing into Small
                        $ImageCreate = $this->createResizeForThumbnail($target_file_normal, $targetfilepath_small, $fileName, $TWH[0], $TWH[1]); 
                        //Creating Thumbnail Image
                        if($ac_width<=$TWH[0] && $ac_height<=$TWH[1])
                            copy($target_file_normal, $targetfilepath_thumb.$fileName);
                        else {
                            list($sm_width, $sm_height) = getimagesize($targetfilepath_small.$fileName);
                            $cropped = $this->resizeThumbnailImage($targetfilepath_thumb.$fileName, $targetfilepath_small.$fileName, $TWH[0],  $TWH[1], ceil(($sm_width/2)-($TWH[0]/2)),ceil(($sm_height/2)-($TWH[1]/2)),1);
                        }
                    }
                    
                    //Creating Large(Resize) Image
                    if($option=='resize' || $option=='all') {
                        if($ac_width<=$LWH[0] && $ac_height<=$LWH[1])
                            copy($target_file_normal, $targetfilepath_large.$fileName);
                        else
                            $ImageCreate = $this->create_resize($target_file_normal, $targetfilepath_large, $fileName, $LWH[0], $LWH[1]);
                    }
                    
                    @unlink($targetfilepath_small.$fileName);
                }
            }
            return $fileName;
        }
        else
            return false;
    }
    
    function autoRotateImage($image)
    {
        $orientation = $image->getImageOrientation();

        switch($orientation) {
            case imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
            break;

            case imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
            break;

            case imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
            break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
    }
}
?>