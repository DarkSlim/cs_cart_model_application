<?php
/*
 * File Uploader class
 * @verion 1.0.0.0
 * @author Anonimus
 * @license GNU Public License
 */

class Uploader {
    //@var string $upload_path this var will hold upload path for all files
    public $upload_path;
    //@var string $temp_path is temporary path for all uploaded files
    public $temp_path;
    //@var array $allowed_extensions contains all extensions that will be allowed for upload
    public $allowed_extensions = array();
    //@var int $max_file_size maximum allowed file size for upload
    public $max_file_size = 250000;
    //@var array $output_errors will contain all errors that may occur during the upload process
    private $output_errors = array();
    /*     * *********************************************************************************************** 
     *  @method void __class construct method
     * ************************************************************************************************ */
    public function __construct() {
        
    }
    /*     * *********************************************************************************************** 
     *  @method mixed _uploadFile uploads single file
     *  @param  array $file this is the $_FILES array object
     * ************************************************************************************************ */
    public function uploadFile($file, $newName) {
       
        if ($_FILES[$file]['size'] == 0) {
            $this->output_errors[] = "Please select file for upload";
            return;
        }

        try {
            if (!empty($this->temp_path)) {
                if (!empty($this->upload_path)) {

                    if (!is_dir($this->temp_path)) {
                        mkdir($this->temp_path);
                    }
                    if (!is_dir($this->upload_path)) {
                        mkdir($this->upload_path);
                    }
                    $fileSize = $_FILES[$file]['size'];
                    $fileType = $_FILES[$file]['type'];
                    $fileError = $_FILES[$file]['error'];
                    $fileTempName = $_FILES[$file]['tmp_name'];
                    $fileName = $_FILES[$file]['name'];

                    //Check file size
                    if ($fileSize <= $this->max_file_size) {
                        //Check file type
                        $allowedExtensions = $this->getExtensionFromFormat($this->allowed_extensions);

                        if (in_array($fileType, $allowedExtensions)) {
                            //Check if file is uploaded
                            if (is_uploaded_file($fileTempName)) {
                                //Move uploaded file to temp directory
                                $tempPath = $this->temp_path . "/" . $fileName;
                                if (move_uploaded_file($fileTempName, $tempPath)) {
                                    //Rename the file and move it to uploads folder
                                    $fileExt = pathinfo($tempPath,4);
                                    $saltFileName = pathinfo($tempPath, 8);
                                    //$newName = $this->generateRandomString($saltFileName);

                                    $uploadPath = $this->upload_path . "/" . $newName . "." . $fileExt;
                                    if ($this->renameFile($tempPath, $uploadPath)) {
                                        $newFilename = $newName . "." . $fileExt;
                                        @unlink($tempPath);
                                        return $newFilename;
                                    }
                                    else {
                                        return false;
                                    }
                                }
                            }
                        }
                        else {
                            $this->output_errors[] = "File has invalid extension";
                        }
                    }
                    else {
                        $this->output_errors[] = "File size exceeds maximum allowed of " . $this->max_file_size;
                    }
                }
                else {
                    throw new Exception("Upload directory parameter is not set, please specify upload directory");
                }
            }
            else {
                throw new Exception("Temp directory parameter is not set, please specify temp directory");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }
    /*     * *********************************************************************************************** 
     *  @method void _uploadMutliFiles uploads multiple files
     *  @param  array  $fileArray this is the $_FILES array object
     * ************************************************************************************************ */
    public function uploadMutliFiles($fileArray) {
        $the_files = $this->fixFileArrayMultiUpload($fileArray);
        $file_names = array();
        if (is_array($the_files)) {

            foreach ($the_files as $file) {
                //If we have file for upload
                if ($file['size'] > 0) {
                    try {
                        if (!empty($this->temp_path)) {
                            if (!empty($this->upload_path)) {

                                if (!is_dir($this->temp_path)) {
                                    mkdir($this->temp_path);
                                }
                                if (!is_dir($this->upload_path)) {
                                    mkdir($this->upload_path);
                                }
                                $fileSize = $file['size'];
                                $fileType = $file['type'];
                                $fileError = $file['error'];
                                $fileTempName = $file['tmp_name'];
                                $fileName = $file['name'];

                                //Check file size
                                if ($fileSize <= $this->max_file_size) {
                                    //Check file type
                                    $allowedExtensions = $this->getExtensionFromFormat($this->allowed_extensions);

                                    if (in_array($fileType, $allowedExtensions)) {
                                        //Check if file is uploaded
                                        if (is_uploaded_file($fileTempName)) {
                                            //Move uploaded file to temp directory
                                            $tempPath = $this->temp_path . "/" . $fileName;
                                            if (move_uploaded_file($fileTempName, $tempPath)) {
                                                //Rename the file and move it to uploads folder
                                                $newName = $this->generateRandomString();
                                                $fileExt = pathinfo($tempPath, PATHINFO_EXTENSION);
                                                $uploadPath = $this->upload_path . "/" . $newName . "." . $fileExt;
                                                if ($this->renameFile($tempPath, $uploadPath)) {
                                                    $newFilename = $newName . "." . $fileExt;
                                                    @unlink($tempPath);
                                                    array_push($file_names, $newFilename);
                                                }
                                                else {
                                                    //upload failed
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        $this->output_errors[] = "File has invalid extension";
                                    }
                                }
                                else {
                                    $this->output_errors[] = "File size exceeds maximum allowed of " . $this->max_file_size;
                                }
                            }
                            else {
                                throw new Exception("Upload directory parameter is not set, please specify upload directory");
                            }
                        }
                        else {
                            throw new Exception("Temp directory parameter is not set, please specify temp directory");
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }
        return $file_names;
    }
    /*     * *********************************************************************************************** 
     *  @method object stdClass fixFileArrayMultiUpload fixes $_FILES array for multile upload
     *  @param  array  $fileArray this is the $_FILES array object
     * ************************************************************************************************ */
    public function fixFileArrayMultiUpload($fileArray) {
        $the_files = $_FILES[$fileArray];
        $ret_array = array();
        if (is_array($the_files)) {

            foreach ($the_files as $key => $obj) {
                foreach ($obj as $i => $val) {
                    $ret_array[$i][$key] = $val;
                }
            }
        }
        return $ret_array;
    }
    /*     * *********************************************************************************************** 
     *  @method bool _renameFile renames and moves file to path specified
     *  @param  string  $filePath this is path of the current uploaded file
     *  @param  string  $newFilePath this is new path of the file after it will be renamed
     * ************************************************************************************************ */
    public function renameFile($filePath, $newFilePath) {
        if (rename($filePath, $newFilePath)) {
            return true;
        }
        return false;
    }
    /*     * *********************************************************************************************** 
     *  @method bool _generateRandomString generates random string
     * ************************************************************************************************ */
    public function generateRandomString($salt, $string_length = 15) {
        $chars = 'a,b,c,d,e,f,g,h,i,j,k,l,m,e,n,o,p,q,r,s,t,u,v,w,x,Y,z,1,2,3,4,5,6,7,8,9,0,!,@,#,$,%,^,&,*,A,B,C,D,E,F,G,H,I,J,K,L,M,E,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,_,-';
        $chars_array = explode(",", $chars);
        $token_length = 30;
        $token = '';
        for ($i = 0; $i < $token_length; $i++) {
            $rand_char = $chars_array[mt_rand(0, count($chars_array) - 1)];
            $token .= $rand_char;
        }
        $token = md5($token . $salt);
        return substr($token, mt_rand(0, (strlen($token) - ($string_length + 1))), $string_length);
    }
    /*     * *********************************************************************************************** 
     *  @method mixed _displayErrors displays any errors from the file upload
     *  @param  string  $success_message displayed if there are no errors
     * ************************************************************************************************ */
    public function displayErrors($success_message = "") {
        if (count($this->output_errors) > 0) {
            $message = "";
            foreach ($this->output_errors as $error) {
                $message .= "<p>" . $error . "</p>";
            }
            return $message;
        }

        if (empty($this->output_errors)) {
            if (!empty($success_message)) {
                echo $success_message;
            }
        }
    }
    /*     * *********************************************************************************************** 
     *  @method array _getExtensionFromFormat displays any errors from the file upload
     *  @param  array  $fileTypes file types
     * ************************************************************************************************ */
    public function getExtensionFromFormat($fileTypes) {
        $allowedExtensions = array();
        $extensions = array(
            "text/h323" => "323",
            "application/internet-property-stream" => "acx",
            "application/postscript" => "ai",
            "audio/x-aiff" => "aif",
            "audio/x-aiff" => "aifc",
            "audio/x-aiff" => "aiff",
            "video/x-ms-asf" => "asf",
            "video/x-ms-asf" => "asr",
            "video/x-ms-asf" => "asx",
            "audio/basic" => "au",
            "video/x-msvideo" => "avi",
            "application/olescript" => "axs",
            "text/plain" => "bas",
            "application/x-bcpio" => "bcpio",
            "application/octet-stream" => "bin",
            "image/bmp" => "bmp",
            "text/plain" => "c",
            "application/vnd.ms-pkiseccat" => "cat",
            "application/x-cdf" => "cdf",
            "application/x-x509-ca-cert" => "cer",
            "application/octet-stream" => "class",
            "application/x-msclip" => "clp",
            "image/x-cmx" => "cmx",
            "image/cis-cod" => "cod",
            "application/x-cpio" => "cpio",
            "application/x-mscardfile" => "crd",
            "application/pkix-crl" => "crl",
            "application/x-x509-ca-cert" => "crt",
            "application/x-csh" => "csh",
            "text/css" => "css",
            "application/x-director" => "dcr",
            "application/x-x509-ca-cert" => "der",
            "application/x-director" => "dir",
            "application/x-msdownload" => "dll",
            "application/octet-stream" => "dms",
            "application/msword" => "doc",
            "application/msword" => "dot",
            "application/x-dvi" => "dvi",
            "application/x-director" => "dxr",
            "application/postscript" => "eps",
            "text/x-setext" => "etx",
            "application/envoy" => "evy",
            "application/octet-stream" => "exe",
            "application/fractals" => "fif",
            "x-world/x-vrml" => "flr",
            "image/gif" => "gif",
            "application/x-gtar" => "gtar",
            "application/x-gzip" => "gz",
            "text/plain" => "h",
            "application/x-hdf" => "hdf",
            "application/winhlp" => "hlp",
            "application/mac-binhex40" => "hqx",
            "application/hta" => "hta",
            "text/x-component" => "htc",
            "text/html" => "htm",
            "text/html" => "html",
            "text/webviewhtml" => "htt",
            "image/x-icon" => "ico",
            "image/ief" => "ief",
            "application/x-iphone" => "iii",
            "application/x-internet-signup" => "ins",
            "application/x-internet-signup" => "isp",
            "image/pipeg" => "jfif",
            "image/jpg" => "jpg",
            "image/jpeg" => "jpg",
            "image/jpeg" => "jpg",
            "image/p-jpeg" => "jpg",
            "application/x-javascript" => "js",
            "application/x-latex" => "latex",
            "application/octet-stream" => "lha",
            "video/x-la-asf" => "lsf",
            "video/x-la-asf" => "lsx",
            "application/octet-stream" => "lzh",
            "application/x-msmediaview" => "m13",
            "application/x-msmediaview" => "m14",
            "audio/x-mpegurl" => "m3u",
            "application/x-troff-man" => "man",
            "application/x-msaccess" => "mdb",
            "application/x-troff-me" => "me",
            "message/rfc822" => "mht",
            "message/rfc822" => "mhtml",
            "audio/mid" => "mid",
            "application/x-msmoney" => "mny",
            "video/quicktime" => "mov",
            "video/x-sgi-movie" => "movie",
            "video/mpeg" => "mp2",
            "audio/mpeg" => "mp3",
            "video/mpeg" => "mpa",
            "video/mpeg" => "mpe",
            "video/mpeg" => "mpeg",
            "video/mpeg" => "mpg",
            "application/vnd.ms-project" => "mpp",
            "video/mpeg" => "mpv2",
            "application/x-troff-ms" => "ms",
            "application/x-msmediaview" => "mvb",
            "message/rfc822" => "nws",
            "application/oda" => "oda",
            "application/pkcs10" => "p10",
            "application/x-pkcs12" => "p12",
            "application/x-pkcs7-certificates" => "p7b",
            "application/x-pkcs7-mime" => "p7c",
            "application/x-pkcs7-mime" => "p7m",
            "application/x-pkcs7-certreqresp" => "p7r",
            "application/x-pkcs7-signature" => "p7s",
            "image/x-portable-bitmap" => "pbm",
            "application/pdf" => "pdf",
            "application/x-pkcs12" => "pfx",
            "image/x-portable-graymap" => "pgm",
            "application/ynd.ms-pkipko" => "pko",
            "application/x-perfmon" => "pma",
            "application/x-perfmon" => "pmc",
            "application/x-perfmon" => "pml",
            "application/x-perfmon" => "pmr",
            "application/x-perfmon" => "pmw",
            "image/x-png" => "png",
            "image/png" => "png",
            "image/x-portable-anymap" => "pnm",
            "application/vnd.ms-powerpoint" => "pot",
            "image/x-portable-pixmap" => "ppm",
            "application/vnd.ms-powerpoint" => "pps",
            "application/vnd.ms-powerpoint" => "ppt",
            "application/pics-rules" => "prf",
            "application/postscript" => "ps",
            "application/x-mspublisher" => "pub",
            "video/quicktime" => "qt",
            "audio/x-pn-realaudio" => "ra",
            "audio/x-pn-realaudio" => "ram",
            "image/x-cmu-raster" => "ras",
            "image/x-rgb" => "rgb",
            "audio/mid" => "rmi",
            "application/x-troff" => "roff",
            "application/rtf" => "rtf",
            "text/richtext" => "rtx",
            "application/x-msschedule" => "scd",
            "text/scriptlet" => "sct",
            "application/set-payment-initiation" => "setpay",
            "application/set-registration-initiation" => "setreg",
            "application/x-sh" => "sh",
            "application/x-shar" => "shar",
            "application/x-stuffit" => "sit",
            "audio/basic" => "snd",
            "application/x-pkcs7-certificates" => "spc",
            "application/futuresplash" => "spl",
            "application/x-wais-source" => "src",
            "application/vnd.ms-pkicertstore" => "sst",
            "application/vnd.ms-pkistl" => "stl",
            "text/html" => "stm",
            "image/svg+xml" => "svg",
            "application/x-sv4cpio" => "sv4cpio",
            "application/x-sv4crc" => "sv4crc",
            "application/x-troff" => "t",
            "application/x-tar" => "tar",
            "application/x-tcl" => "tcl",
            "application/x-tex" => "tex",
            "application/x-texinfo" => "texi",
            "application/x-texinfo" => "texinfo",
            "application/x-compressed" => "tgz",
            "image/tiff" => "tif",
            "image/tiff" => "tiff",
            "application/x-troff" => "tr",
            "application/x-msterminal" => "trm",
            "text/tab-separated-values" => "tsv",
            "text/plain" => "txt",
            "text/iuls" => "uls",
            "application/x-ustar" => "ustar",
            "text/x-vcard" => "vcf",
            "x-world/x-vrml" => "vrml",
            "audio/x-wav" => "wav",
            "application/vnd.ms-works" => "wcm",
            "application/vnd.ms-works" => "wdb",
            "application/vnd.ms-works" => "wks",
            "application/x-msmetafile" => "wmf",
            "application/vnd.ms-works" => "wps",
            "application/x-mswrite" => "wri",
            "x-world/x-vrml" => "wrl",
            "x-world/x-vrml" => "wrz",
            "x-world/x-vrml" => "xaf",
            "image/x-xbitmap" => "xbm",
            "application/vnd.ms-excel" => "xla",
            "application/vnd.ms-excel" => "xlc",
            "application/vnd.ms-excel" => "xlm",
            "application/vnd.ms-excel" => "xls",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "xlsx",
            "application/vnd.ms-excel" => "xlt",
            "application/vnd.ms-excel" => "xlw",
            "x-world/x-vrml" => "xof",
            "image/x-xpixmap" => "xpm",
            "image/x-xwindowdump" => "xwd",
            "application/x-compress" => "z",
            "application/zip" => "zip"
        );

        foreach ($extensions as $type => $ext) {
            if (in_array($ext, $fileTypes)) {
                array_push($allowedExtensions, $type);
            }
        }
        return $allowedExtensions;
    }
}

?>