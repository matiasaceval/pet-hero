<?php namespace Utils;

use Exception;

abstract class GenerateFile
{
    /**
     * @throws Exception
     */
    public static function PersistFile(array $file, string $prefix, int $id, string $suffix = '', $deleteAllWithDifferentType = true): ?string
    {
        $fileExt = explode(".", $file["name"]);
        $fileType = strtolower(end($fileExt));
        $filePreName = $prefix . $id . $suffix;
        $fileName = $filePreName . "." . $fileType;

        $tempFileName = $file["tmp_name"];
        $filePath = UPLOADS_PATH . basename($fileName);
        $fileSize = filesize($tempFileName);

        if ($fileSize !== false) {
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
                Session::Set("error", "Error uploading file");
            }
        } else {
            Session::Set("error", "Error uploading file");
        }
        return null;
    }
}