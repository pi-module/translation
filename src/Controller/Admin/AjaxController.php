<?php
/**
 * Translation module ajax controller
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) http://www.eefocus.com
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Chuang Liu <liuchuang@eefocus.com>
 * @since           1.0
 * @package         Module\Translation
 */

namespace Module\Translation\Controller\Admin;

use Pi\Mvc\Controller\ActionController;
use Pi;

class AjaxController extends ActionController
{
    const AJAX_RESULT_TRUE     = 1;
    const AJAX_RESULT_FALSE    = 0;
    const AJAX_MESSAGE         = 'ok';
    protected $tree;
    protected $treeId = 1;

    public function indexAction()
    {
    }

    /**
     * Check source path.
     *
     * @return array
     */
    public function checkSourcePathAction()
    {
        Pi::service('log')->active(false);
        $path = $this->params('path', '');
        $path = Pi::path('www') . '/upload/translation/source/' . $path;
        if (!is_dir($path)) {
            $result = 2;
        } elseif (!$this->file_exit($path)) {
            $result = 3;
        } else {
            $result = 1;
        }
        return array(
            'status'    => self::AJAX_RESULT_TRUE,
            'message'   => self::AJAX_MESSAGE,
            'valid'     => $result,
        );
    }

    /**
     * Check output path.
     *
     * @return array
     */
    public function checkOutputPathAction()
    {
        Pi::service('log')->active(false);
        $path = $this->params('path', '');
        $path  = Pi::path('www') . '/upload/translation/output/' . $path;

        if (is_dir($path) && $this->file_exit($path)) {
            $result = false;
        } else {
            $result = true;
        }


        return array(
            'status'    => self::AJAX_RESULT_TRUE,
            'message'   => self::AJAX_MESSAGE,
            'valid'     => $result,
        );
    }

    /**
     * Get skip path.
     *
     * @return array
     */
    public function getSkipPathAction()
    {
        Pi::service('log')->active(false);

        $mode = $this->params('mode', '');
        $skipPath = $this->config($mode . '_mode_skip_path', '');

        return array(
            'status'     => $skipPath ? self::AJAX_RESULT_TRUE : self::AJAX_RESULT_FALSE,
            'message'    => self::AJAX_MESSAGE,
            'skip'       => $skipPath,
        );
    }

    /**
     * Get directory tree structure.
     *
     * @return mixed
     */
    public function getTreeAction()
    {
        Pi::service('log')->active(false);

        $path = $this->params('path', '');
        $path = Pi::path('www') . '/upload/translation/source/' . $path;
        $this->file_list($path, '', '0');
        return $this->tree;
    }

    /**
     * Check path is exit
     *
     * @param $filelname
     * @return bool
     */
    protected function file_exit($filename)
    {

        if($filename != ''){
            $handle = opendir($filename);
        }else{
            $handle = opendir($filename);
        }
        while (false !== ($file = readdir($handle))) {
            if($file == '.' || $file == '..'){
                continue;
            }
            $file_array[] = $file;
        }
        if($file_array == NULL){
            closedir($handle);
            return false;
        }
        closedir($handle);
        return true;
    }

    /**
     * Get file list for path.
     *
     * @param $dir
     * @param string $pattern
     * @param int $parent
     */
    protected function file_list($dir, $pattern = "", $parent = 0)
    {
        $dir_handle = opendir($dir);
        if ($dir_handle) {
            while(($file = readdir($dir_handle))!== false) {
                if ($file === '.' || $file == '..' || preg_match('/^\./', $file)) {
                    continue;
                }
                $tmp = realpath($dir.'/'.$file);
                if(is_dir($tmp) && $file != 'image' && $file != 'local') {
                    $this->tree[] = array(
                        'id'   => $parent + $this->treeId,
                        'name' => $file,
                        'pId'  => $parent,
                    );
                    $this->file_list($tmp, '', $parent + $this->treeId);
                    $this->treeId++;
                } else {
                    if($pattern === "" || preg_match($pattern,$tmp)) {
                        $this->tree[] = array(
                            'id'   => $parent + $this->treeId,
                            'name' => $file,
                            'pId'  => $parent,
                        );
                        $this->treeId++;
                    }
                }
            }
            closedir($dir_handle);
        }
    }
}