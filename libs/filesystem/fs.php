<?php

/**
 * @package     Synapse
 * @subpackage  File System
 */


defined('_INIT') or die;


class FS {


    public static function exists($path)
    {
        return file_exists($path);
    }

    public static function rename($old, $new)
    {
        return rename($old, $new);
    }

    public static function delete($path)
    {
        return unlink($path);
    }

    public static function zip($files = array(), $destination = '', $overwrite = false)
    {
        //if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite)
        {
            return false;
        }

        //vars
        $valid_files = array();
        //if files were passed in...
        if (is_array($files))
        {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }

        //if we have good files...
        if (count($valid_files))
        {
            //create the archive
            $zip = new ZipArchive();
            if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach ($valid_files as $file) {
                $zip->addFile($file, $file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

    public static function unzip()
    {

    }
}

?>