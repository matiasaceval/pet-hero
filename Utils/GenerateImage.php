<?php namespace Utils;

use Exception;

abstract class GenerateImage
{
    /**
     * @throws Exception
     */
    public static function PersistImage(array $image, string $prefix, int $id, string $suffix = '', $deleteAllWithDifferentType = true): ?string
    {
        $fileExt = explode(".", $image["name"]);
        $fileType = strtolower(end($fileExt));
        $filePreName = $prefix . $id . $suffix;
        $fileName = $filePreName . "." . $fileType;
        $tempFileName = $image["tmp_name"];
        $filePath = UPLOADS_PATH . basename($fileName);
        $imageSize = getimagesize($tempFileName);

        if ($imageSize !== false) {

            if ($deleteAllWithDifferentType) {
                $files = glob(UPLOADS_PATH . $filePreName . ".*");
                foreach ($files as $file) {
                    chmod($file, 0755); //Change the file permissions if allowed
                    unlink($file); //remove the file
                }
            }

            if (move_uploaded_file($tempFileName, $filePath)) {
                return $fileName;
            } else {
                Session::Set("error", "Error uploading image");
            }
        } else {
            Session::Set("error", "File is not an image");
        }
        return null;
    }
}