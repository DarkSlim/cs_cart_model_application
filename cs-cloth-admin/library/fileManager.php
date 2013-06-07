<?php
/*
 * File manipulation class
 * @verion 1.0.0.0
 * @author Pazarkoski Riste
 * @license GNU Public License
 */

class FileManager {
    /*     * *********************************************************************************************** 
     *  @method void  class construct method
     * ************************************************************************************************ */
    public function __construct() {
        
    }
    /*     * *********************************************************************************************** 
     *  @method mixed creates new file
     *  @param $file_path destination of the new file
     *  @param $override if true will override the existing file otherwise won't 
     * ************************************************************************************************ */
    public static function touchFile($file_path, $filePermissions = 0644, $override = false) {
        try {
            if ($override == false) {
                if (!file_exists($file_path)) {
                    //Create the file
                    if (@touch($file_path)) {
                        //Set file permissions
                        @chmod($file_path, $filePermissions);
                    }
                    else {
                        throw new Exception('Unable to create file <strong>' . $file_path . "</strong>");
                    }
                }
            }
            else if ($override == true) {
                //Create the file
                if (@touch($file_path)) {
                    //Set file permissions
                    @chmod($file_path, $filePermissions);
                }
                else {
                    throw new Exception('Unable to create file <strong>' . $file_path . "</strong>");
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method boolean delete file
     *  @param $file_path the path to the file
     * ************************************************************************************************ */
    public static function deleteFile($file_path) {
        if (file_exists($file_path)) {
            if (@unlink($file_path)) {
                return true;
            }
        }
        return false;
    }
    /*     * *********************************************************************************************** 
     *  @method int returns file permissions
     *  @param $file_path the path to the file
     * ************************************************************************************************ */
    public static function getFilePermisions($file_path) {
        return substr(sprintf('%o', fileperms($file_path)), -4);
    }
    /*     * *********************************************************************************************** 
     *  @method int returns file permissions
     *  @param $file_path the path to the file
     * ************************************************************************************************ */
    public static function setFilePermisions($file_path, $filePermissions = 0644) {
        @chmod($file_path, $filePermissions);
    }
    /*     * *********************************************************************************************** 
     *  @method mixed read file content
     *  @param $file_path the path to the file
     * ************************************************************************************************ */
    public static function readFile($file_path) {
        try {
            if (file_exists($file_path)) {
                if (is_readable($file_path)) {
                    $content = file_get_contents($file_path);
                    return $content;
                }
                else {
                    throw new Exception("File <strong>" . $file_path . "</strong> is not readable");
                }
            }
            else {
                throw new Exception("File <strong>" . $file_path . "</strong> does not exist.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method mixed read file content
     *  @param $file_path the path to the file
     * ************************************************************************************************ */
    public static function readFileIntoArray($file_path, $separator = "\n") {
        try {
            if (file_exists($file_path)) {
                if (is_readable($file_path)) {
                    //Open the file
                    $fp = @fopen($file_path, 'r');
                    if ($fp) {
                        //Lock the file
                        if (flock($fp, LOCK_EX)) {
                            $array = explode($separator, fread($fp, filesize($file_path)));
                            // release the lock
                            flock($fp, LOCK_UN);
                            fclose($fp);
                            return $array;
                        }
                        else {
                            throw new Exception("File <strong>" . $file_path . "</strong> can not be locked.");
                        }
                    }
                }
                else {
                    throw new Exception("File <strong>" . $file_path . "</strong> is not readable");
                }
            }
            else {
                throw new Exception("File <strong>" . $file_path . "</strong> does not exist.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void write contents to file
     *  @param $file_path the path to the file
     *  @param $content 
     *  @param $flags write : w - write append : a+ default w 
     * ************************************************************************************************ */
    public static function writeToFile($file_path, $content, $flags = "w") {
        try {
            if (file_exists($file_path)) {
                if (is_writable($file_path)) {
                    //Open the file
                    $fp = @fopen($file_path, $flags);
                    if ($fp) {
                        //Lock the file
                        if (flock($fp, LOCK_EX)) {
                            //write to file
                            fwrite($fp, $content);
                            // release the lock
                            flock($fp, LOCK_UN);
                            fclose($fp);
                            return true;
                        }
                        else {
                            throw new Exception("File <strong>" . $file_path . "</strong> can not be locked.");
                        }
                    }
                }
                else {
                    throw new Exception("File <strong>" . $file_path . "</strong> is not writable");
                }
            }
            else {
                throw new Exception("File <strong>" . $file_path . "</strong> does not exist.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void copy file
     *  @param $source the path to the file
     *  @param $dest
     * ************************************************************************************************ */
    public static function copyFile($source, $dest) {
        try {
            if (file_exists($source)) {
                if (!copy($source, $dest)) {
                    throw new Exception("Failed to copy <strong>" . $source . "</strong>");
                }
            }
            else {
                throw new Exception("File <strong>" . $source . "</strong> could not be copied, the file does not exist.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void move file
     *  @param $source the path to the file
     *  @param $dest destination of the file
     * ************************************************************************************************ */
    public static function moveFile($source, $dest) {
        try {
            if (file_exists($source)) {
                if (!rename($source, $dest)) {
                    throw new Exception("Failed to move <strong>" . $source . "</strong>");
                }
            }
            else {
                throw new Exception("File <strong>" . $source . "</strong> could not be moved, the file does not exist.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void list files from directory
     *  @param $source_dir the path to the file
     *  #param $sorting_order soritng order 0 - SCANDIR_SORT_ASCENDING , 1 - SCANDIR_SORT_DESCENDING, 2 - SCANDIR_SORT_NONE
     * ************************************************************************************************ */
    public static function listDirectoryFiles($source_dir, $sorting_order = 0) {
        try {
            if (is_dir($source_dir)) {
                $dirData = scandir($source_dir, $sorting_order);
                $data_array = array();
                //Files will be stored in this array 
                $files_array = array();
                //direcotries will be stored in this array
                $dir_array = array();
                foreach ($dirData as $file) {
                    if ($file != "." && $file != "..") {
                        //check for forward slash in $source_dir
                        $file_path = "";
                        if (strcasecmp("/", substr($source_dir, -1)) == 0) {
                            $file_path = $source_dir . $file;
                        }
                        else {
                            $file_path = $source_dir . "/" . $file;
                        }
                        if (is_file($file_path)) {
                            array_push($files_array, $file);
                        }
                        else if (is_dir($file_path)) {
                            array_push($dir_array, $file);
                        }
                    }
                }
                //App end files and directory arrays to data_array
                array_push($data_array, $files_array, $dir_array);
                return $data_array;
            }
            else {
                throw new Exception("<strong>" . $source_dir . "</strong> is not valid directory.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void make new directory
     *  @param $dir_path the path of the new directory
     *  @param $dir_permissions directory permissions
     * ************************************************************************************************ */
    public static function makeDir($dir_path, $dir_permissions = 0755, $recrusive = false) {
        try {
            if (!is_dir($dir_path)) {
                if (!@mkdir($dir_path, $dir_permissions, $recrusive)) {
                    throw new Exception("Directory <strong>" . $dir_path . "</strong> can not be created.");
                }
                return true;
            }
            else {
                throw new Exception("Directory <strong>" . $dir_path . "</strong> already exists.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*     * *********************************************************************************************** 
     *  @method void delete directory
     *  @param $dir_path the path of the directory
     * ************************************************************************************************ */
    public static function removeDir($dir_path) {
        try {
            if (file_exists($dir_path)) {
                if (is_dir($dir_path)) {
                    $dir = new DirectoryIterator($dir_path);
                    foreach ($dir as $fileinfo) {
                        if ($fileinfo->isFile() || $fileinfo->isLink()) {
                            unlink($fileinfo->getPathName());
                        }
                        elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
                            self::removeDir($fileinfo->getPathName());
                        }
                    }
                    rmdir($dir_path);
                    return true;
                }
                else {
                    throw new Exception("<strong>" . $dir_path . "</strong> is not valid <strong>directory</strong>.");
                }
            }
            else {
                throw new Exception("Directory <strong>" . $dir_path . "</strong> doesn't exists.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>