<?php

namespace Module\Translation;

use Pi;

class Translation
{
    public function  makeZip($source, $destination, array $option)
    {
        if (!$option) {
            return false;
        }

        // Zip to file
        if ('file' == $option['type']) {
            $zip = new \ZipArchive();
            if ($zip->open($destination, \ZipArchive::OVERWRITE) === TRUE) {
                $zip->addFile($source);
                $zip->close();
            }
            return;
        }

        // Zip to Dir
        if ('dir' == $option['type']) {
            $zip = new \ZipArchive();
            if ($zip->open($destination, \ZipArchive::OVERWRITE) === TRUE) {
                $base = $option['base'];
                $this->addFileToZip($source, $zip, $base);
                $zip->close();
            }
            return $destination;
        }
    }

    public function downLoad($file)
    {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            return true;
        } else {
            return false;
        }
    }


    protected  function addFileToZip($path, $zip, $base)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler))!== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($path . "/" . $filename)) {
                    $this->addFileToZip($path. "/" . $filename, $zip, $base);
                } else {
                    $pa = str_replace($base, '', $path);
                    $zip->addFile($path . "/" . $filename, $pa . '/' . $filename);
                }
            }
        }
        @closedir($handler);
    }
}

