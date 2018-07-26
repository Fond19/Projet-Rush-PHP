<?php
define('TARGET', __DIR__ . "/uploads/");
define('MAX_SIZE', 100000);
define('WIDTH_MAX', 2500);
define('HEIGHT_MAX', 2500);

class Image
{

    protected static $extensions = array('jpg', 'gif', 'png', 'jpeg');

    public static function upload($post, $files)
    {
        $infosImg = array();
        $extension = '';
        $message = '';
        $imageName = '';
        {
            if (!empty($files['upload']['name'])) {
                $extension = pathinfo($files['upload']['name'], PATHINFO_EXTENSION);

                if (in_array(strtolower($extension), SELF::$extensions)) {
                    $infosImg = getimagesize($files['upload']['tmp_name']);

                    if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                        if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['upload']['tmp_name']) <= MAX_SIZE)) {
                            if (isset($_FILES['upload']['error'])
                                && UPLOAD_ERR_OK === $files['upload']['error']) {
                                $imageName = md5(uniqid()) . '.' . $extension;

                                if (move_uploaded_file($files['upload']['tmp_name'], TARGET . $imageName)) {
                                    $message = 'Upload réussi !';
                                    return ($imageName);
                                } else {
                                    $message = 'Problème lors de l\'upload !';
                                }
                            } else {
                                $message = 'Une erreur interne a empêché l\'uplaod de l\'image';
                            }
                        } else {
                            $message = 'Erreur dans les dimensions de l\'image !';
                        }
                    } else {
                        $message = 'Le fichier à uploader n\'est pas une image !';
                    }
                } else {
                    $message = 'L\'extension du fichier est incorrecte !';
                }
            } else {
                $message = 'Veuillez remplir le formulaire svp !';
            }
        }
        //echo $message;
    }
}
