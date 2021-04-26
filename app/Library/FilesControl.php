<?php

namespace App\Library;

use Illuminate\Support\Facades\Config;
use Image;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class FilesControl
{

    public static function getPathImages($pFiles = false)
    {
        $configFile = Config::get('filesystems');

        if (env('APP_ENV') == 'local') {
            $pathImages = $configFile['APP_URL_PATH_PUBLIC'];
            if($pFiles){
                $pathImages = $pathImages.'/../../files/images';
            }
        } else {
            $pathImages = $configFile['APP_URL_PATH_STORAGE'];
            if($pFiles){
                $pathImages = $pathImages.'/../../files/images';
            }
        }

        return $pathImages;
    }

    public static function resizeImage($pPathObject, $pPathFile, $pFileSave, $pFileInput, $pWidth, $pHeight)
    {

        $fileName = $pFileInput->getClientOriginalName();

        if(is_dir($pPathObject)){
            try {
                Image::make($pFileSave)->resize($pWidth,$pHeight)->save($pPathFile);
            } catch (\Exception $exception) {
                throw new \ErrorException('Erro ao redimensionar a imagem: ('.$fileName.'). '.$exception->getMessage());
            }
        } else {
            throw new \ErrorException('Erro ao redimensionar a imagem: ('.$fileName.'). Pasta do objeto não existe!');
        }

    }

    public static function saveImage($pImageObjectSave, $pImageObjectInput, $pPathObject, $pIDObject,
                                     $pOPCSaveResize = array (
                                         1 => 'All_Sizes',
                                         2 => 'Large',
                                         3 => 'Medium',
                                         4 => 'Small'
                                     )
    ){

        $pathImages = storage_path('app/public/upload/images');
        $pathSaveObject = $pathImages.'/'.$pPathObject.'/'.$pIDObject;

        $large_path   = $pathSaveObject.'/large';
        $medium_path  = $pathSaveObject.'/medium';
        $small_path   = $pathSaveObject.'/small';

        if($pImageObjectInput->isValid()){

            $fileName = $pImageObjectInput->getClientOriginalName();

            if($pImageObjectSave === $pImageObjectInput){
                $fileExtension = $pImageObjectInput->getClientOriginalExtension();
            } else {
                $image_parts = explode(";base64,", $pImageObjectSave);
                $image_type_aux = explode("image/", $image_parts[0]);
                $fileExtension = $image_type_aux[1];
            }

            //sha1(time().time()).".{$fileExtension}";
            //rand(111,99999).'.'.$fileExtension;
            $saveFileName = sha1(time().rand(111,99999)).".{$fileExtension}";

            $large_image_path   = $large_path.'/'.$saveFileName;
            $medium_image_path  = $medium_path.'/'.$saveFileName;
            $small_image_path   = $small_path.'/'.$saveFileName;

            switch ($pOPCSaveResize) {
                case 1: //All_Sizes

                    if(!(is_dir($large_path))){
                        mkdir($large_path, 0777, true);
                    }

                    if(!(is_dir($medium_path))){
                        mkdir($medium_path, 0777, true);
                    }

                    if(!(is_dir($small_path))){
                        mkdir($small_path, 0777, true);
                    }

                    //large
                    try {
                        Image::make($pImageObjectSave)->save($large_image_path);
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao salvar a imagem: ('.$fileName.'). '.$exception->getMessage());
                    }
                    //medium
                    self::resizeImage($medium_path,$medium_image_path,$pImageObjectSave, $pImageObjectInput, 550,450);
                    //small
                    self::resizeImage($small_path,$small_image_path,$pImageObjectSave, $pImageObjectInput,200,170);

                    break;
                case 2: //large

                    if(!(is_dir($large_path))){
                        mkdir($large_path, 0777, true);
                    }

                    //large
                    try {
                        Image::make($pImageObjectSave)->save($large_image_path);
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao salvar a imagem: ('.$fileName.'). '.$exception->getMessage());
                    }

                    break;
                case 3: //medium

                    if(!(is_dir($medium_path))){
                        mkdir($medium_path, 0777, true);
                    }

                    //medium
                    self::resizeImage($medium_path,$medium_image_path,$pImageObjectSave, $pImageObjectInput, 550,450);

                    break;
                case 4: //small

                    if(!(is_dir($small_path))){
                        mkdir($small_path, 0777, true);
                    }

                    //small
                    self::resizeImage($small_path,$small_image_path,$pImageObjectSave, $pImageObjectInput,200,170);

                    break;
                default:
                    throw new \ErrorException('Erro com a opção do resize da imagem');
            }

            return $saveFileName;

        } else {
            throw new \ErrorException('Erro ao salvar a imagem: ('.$pImageObjectSave->getClientOriginalName().'). Verifique o tipo da imagem!');
        }

    }

    public static function deleteImage($pPathObjectID, $pFileImage)
    {
        $deleteImageLarge   = true;
        $deleteImageMedium  = true;
        $deleteImageSmall   = true;

        $dirLarge   = $pPathObjectID.'/large';
        $dirMedium  = $pPathObjectID.'/medium';
        $dirSmall   = $pPathObjectID.'/small';

        $fileLarge   = $pPathObjectID.'/large/'.$pFileImage;
        $fileMedium  = $pPathObjectID.'/medium/'.$pFileImage;
        $fileSmall   = $pPathObjectID.'/small/'.$pFileImage;

        if(is_dir($dirLarge)){
            if(is_file($fileLarge)){
                if(unlink($fileLarge)){
                    $deleteImageLarge   = true;
                } else {
                    $deleteImageLarge   = false;
                }
            }
        }

        if(is_dir($dirMedium)){
            if(is_file($fileMedium)){
                if(unlink($fileMedium)){
                    $deleteImageMedium   = true;
                } else {
                    $deleteImageMedium   = false;
                }
            }
        }

        if(is_dir($dirSmall)){
            if(is_file($fileSmall)){
                if(unlink($fileSmall)){
                    $deleteImageSmall   = true;
                } else {
                    $deleteImageSmall   = false;
                }
            }
        }

        if($deleteImageLarge && $deleteImageMedium  && $deleteImageSmall){
            return true;
        } else {
            return false;
        }

    }

    public static function deleteImageAndPath($pPathObject)
    {

        $iterator     = new RecursiveDirectoryIterator($pPathObject,\FilesystemIterator::SKIP_DOTS);
        $rec_iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($rec_iterator as $file){
            $file->isFile() ? unlink($file->getPathname()) : rmdir($file->getPathname());
        }

        if(rmdir($pPathObject)){
            return true;
        } else {
            return false;
        }

    }
}
