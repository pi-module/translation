<?php
/**
 * Action controller class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Liu Chuang <liuchuang@eefocus.com>
 * @since           3.0
 * @package         Module\Translation
 * @subpackage      Controller
 * @version         $Id$
 */

namespace Module\Translation\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Translation\Form\TranslationForm;
use Module\Translation\Form\TranslationFilter;
use Zend\Db\Sql\TableIdentifier;
use Module\Translation\Translation;
use Pi\File\Transfer\Download;


/**
 * Index action controller
 */
class IndexController extends ActionController
{
    /**
     * Initialize data
     * @var array
     */
    protected $initializeData = array();

    /**
     * Source path all file list
     * @var array
     */
    protected $fileList = array();

    /**
     * Output path
     * @var string
     */
    protected $outputPath = '';

    /**
     * All process result file list
     * @var array
     */
    protected $resultFileList = array();


    /**
     * Process start
     * 1. Make output path
     * 2. Prepare file list
     * 3. Process
     * 4. Display process result
     * 5. Download zip
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $form = new TranslationForm;
            $data = $this->request->getPost();
            $form->setInputFilter(new TranslationFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                $this->setData($values);

                // Check mode and path
                if ($values['mode'] != 'custom' && !$this->checkPathStructure($values['mode'], $values['source-path'])) {
                    $this->view()->assign('form', $form);
                    $this->view()->assign('modeError', 1);
                    return;
                }

                // Make output path
                if (!is_dir($this->outputPath)) {
                    Pi::service('file')->mkdir($this->initializeData['output']);
                    $this->outputPath = $this->initializeData['output'];
                }

                // Set file list according source.
                $list = $this->getFileList($this->initializeData['mode']);
                if (!$list) {
                    return;
                }

                // Set temp file list.
                $tempFileList = $this->getTempFileList($this->initializeData['mode'], $list);

                // Translation according temFileList.
                if (!$tempFileList) {
                    return;
                }
                if ('pi' == $this->initializeData['mode']) {
                    $this->translation(array('pi',$tempFileList));
                } else {
                    $this->translation(
                        array(
                            $this->initializeData['mode'],
                            $tempFileList,
                        )
                    );
                }

                // Display process result
                if ($this->resultFileList) {
                    foreach ($this->resultFileList as &$file) {
                        $file = str_replace(Pi::path('www') . '/', '', $file);
                    }

                    $this->view()->assign(array(
                        'result'       => 1,
                        'resultList'   => $this->resultFileList,
                        'output'       => $this->outputPath,
                    ));

                    if ('custom' == $this->initializeData['mode']) {
                        $this->view()->assign('filename', $this->initializeData['outputName']);
                    }
                }
            }
        } else {
            $form = $this->getTranslationForm();
        }

        $this->view()->assign('form', $form);
    }

    /**
     * Download zip file
     */
    public function downloadAction()
    {
        $path     = urldecode($this->params('path', ''));
        $filename = $this->params('filename', '');

        if (!is_dir($path)) {
            $this->jump(array('action' => 'index'), __('Download file fail'), 3);
            return;
        }

        if ($filename) {
            $destination = $path . '/' . $filename . '.csv';
        } else {
            $source         = $path;
            $outputPathName = strrchr($source, '/');
            $des            = $path . $outputPathName . '.zip';
            $base           = Pi::path('www') . '/upload/translation/output';
            $translation    = new Translation();
            $destination    = $translation->makeZip($source,$des, array('type' => 'dir', 'base' => $base));
        }

        if (file_exists($destination)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($destination));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($destination));
            ob_clean();
            flush();
            readfile($destination);
        }
        $this->view()->setTemplate(false);
    }

    /**
     * Set process file list.
     *
     * @param $mode    Process mode.
     */
    protected function getFileList($mode)
    {
        if ('pi' == $mode) {
            $list = array(
                'system'   => '',
                'modules'  => array(),
                'themes'   => array(),
            );
            // Get system file list.
            $this->fileList = array();
            $this->file_list($this->initializeData['source'] . '/lib');
            $this->file_list($this->initializeData['source'] . '/doc');
            $list['system'] = $this->fileList ?: array();

            // Get modules file list
            $moduelPath = $this->initializeData['source'] . '/usr/module';
            $modules = $this->getDir($moduelPath);
            if ($modules) {
                foreach ($modules as $module) {
                    $path = $this->initializeData['source'] . '/usr/module/' . $module;
                    $this->fileList = array();
                    $this->file_list($path);
                    $list['modules'][$module] = $this->fileList ?: array();
                }
            }

            // Get theme file list
            $themPath = $this->initializeData['source'] . '/usr/theme';
            $themes   = $this->getDir($themPath);
            if ($themes) {
                foreach ($themes as $theme) {
                    $path = $this->initializeData['source'] . '/usr/theme/' . $theme;
                    $this->fileList = array();
                    $this->file_list($path);
                    $list['themes'][$theme] = $this->fileList ?: array();
                }
            }

        }

        if ('module' == $mode) {
            $list = array();
            $modulePath = $this->initializeData['source'];
            $args       = explode('/', $modulePath);
            $module     = array_pop($args);
            if (!$module) {
                $module = array_pop($args);
            }
            $this->file_list($modulePath);
            $list[$module] = $this->fileList;
        }

        if ('theme' == $mode) {
            $list = array();
            $modulePath = $this->initializeData['source'];
            $args       = explode('/', $modulePath);
            $module     = array_pop($args);
            if (!$module) {
                $module = array_pop($args);
            }
            $this->file_list($modulePath);
            $list[$module] = $this->fileList;

            return $list;
        }

        if ('custom' == $mode) {
            $list = array();
            $custom = explode(',', $this->initializeData['checkedFile']);
            foreach ($custom as $file) {
                $list['custom'][] = $this->initializeData['source'] . '/' . $file;
            }
            return $list;
        }

        return $list;
    }

    /**
     * Make temp file list and return temp file list.
     *
     * @param $mode   Mode.
     * @param $list   Process file list.
     * @return array  temp file list
     */
    protected function getTempFileList($mode, $list)
    {
        $tempFileList = array();
        if ('pi' == $mode) {
            foreach($list as $key => $mode) {
                if ($key != 'system') {
                    foreach ($mode as $name => $fileList ) {
                        if ('modules' == $key) {
                            $tempFile                 = $this->outputPath . '/module-' . $name . '-tmp';
                            $tempFileList['module'][] = $tempFile;
                            $fp                       = fopen($tempFile, 'a');
                        } else {
                            $tempFile                = $this->outputPath . '/theme-' . $name . '-tmp';
                            $tempFileList['theme'][] = $tempFile;
                            $fp                      = fopen($tempFile, 'a');
                        }
                        while (!empty($fileList)) {
                            $file = array_pop($fileList);
                            if ($this->filter($file)) {
                                continue;
                            }
                            $content = file_get_contents($file);
                            fwrite($fp, $content);
                            unset($content);
                        }
                        fclose($fp);
                    }
                } else {
                    $tempFile                 = $this->outputPath . '/system-system-tmp';
                    $tempFileList['system'][] = $tempFile;
                    $fp                       = fopen($tempFile, 'a');

                    while (!empty($mode)) {
                        $file = array_shift($mode);
                        if ($this->filter($file)) {
                            continue;
                        }
                        $content = file_get_contents($file);
                        fwrite($fp, $content);
                        unset($content);
                    }
                    fclose($fp);
                }
            }
        }

        if ('module' == $mode || 'theme' == $mode || 'custom' == $mode) {
            $module = array_keys($list);
            $module = end($module);

            // Make temp file
            $tempFile = $this->outputPath . '/' . $mode . '-' . $module . '-temp';
            $fp       = fopen($tempFile, 'a');

            if (!$fp) {
                return;
            }

            foreach ($list[$module] as $file) {
                $content = file_get_contents($file);
                fwrite($fp, $content);
            }
            fclose($fp);

            $tempFileList = array(
                $module => array($tempFile),
            );
        }

        return $tempFileList;
    }

    /**
     *  Initialize process data
     *  $value   Submit data according from
     */
    protected function setData($value)
    {
        $baseOutputPath = Pi::path('www') . '/upload/translation/output/';
        $baseSourcePath = Pi::path('www') . '/upload/translation/source/';
        // Set skip path array.
        $skip = trim($value['skip-path']);
        $skip = str_replace("\r", "\n", $skip);
        $skip = explode("\n", $skip);
        $skip = array_filter($skip);

        $data = array(
            'source'      => trim($value['source-path']) ? $baseSourcePath . trim($value['source-path']) : trim($value['source-path']) . 'default' ,
            'output'      => trim($value['output-path']) ? $baseOutputPath . trim($value['output-path']) : $baseOutputPath . 'default',
            'mode'        => trim($value['mode']),
            'skip'        => $skip,
            'baseOutput'  => $baseOutputPath,
            'checkedFile' => $value['checked-file'],
            'outputName'  => trim($value['output-file-name']) ? trim($value['output-file-name']) : 'main',
        );

        $this->initializeData = $data;
        return;
    }

    /**
     * @return TranslationForm
     */
    protected function getTranslationForm()
    {
        $form = new TranslationForm;
        $form->setAttribute('action', $this->url('', array('action' => 'index')));
        return $form;
    }

    /**
     * Translate file list contents
     *
     * @param $list temp file list
     */
    protected function translation($list)
    {
        $pattern1 = '/__\(\'(.*?)\'\)/';
        $pattern2 = '/_e\(\'(.*?)\'\)/';
        $pattern3 = '/__\(\"(.*?)\"\)/';
        $pattern4 = '/_e\(\"(.*?)\"\)/';

        $mode = array_shift($list);
        $list = array_shift($list);

        foreach ($list as $value) {
            foreach ($value as $temFile ) {
                $content = file_get_contents($temFile);
                preg_match_all($pattern1, $content, $m1);
                preg_match_all($pattern2, $content, $m2);
                preg_match_all($pattern3, $content, $m3);
                preg_match_all($pattern4, $content, $m4);
                unset($content);
                $keys = array();
                if (!empty($m1[1])) {
                    $keys[] = $m1[1];
                }
                if (!empty($m2[1])) {
                    $keys[] = $m2[1];
                }
                if (!empty($m3[1])) {
                    $keys[] = $m3[1];
                }
                if (!empty($m4[1])) {
                    $keys[] = $m4[1];
                }

                foreach ($keys as $key) {
                    foreach ($key as $k) {
                        $keyWord[] =  stripslashes($k);
                    }
                }
                $keyWord = array_unique($keyWord);

                unset($keys);
                if ('pi' == $mode) {
                    list($type, $module, $tmp) = explode('-', basename($temFile));
                    unset($tmp);
                    if ('system' == $type) {
                        $targetFile = $this->outputPath . '/usr/locale/en';

                    }
                    if ('module' == $type) {
                        $targetFile = $this->outputPath . '/usr/module/' . $module . '/locale/en';
                    }

                    if ('theme' == $type) {
                        $targetFile = $this->outputPath . '/usr/theme/' . $module . '/locale/en';
                    }
                    Pi::service('file')->mkdir($targetFile);

                    $fp = fopen($targetFile . '/main.csv', 'a');
                    foreach ($keyWord as $value) {
                        fputcsv($fp, array($value, $value));
                    }
                    fclose($fp);
                    $this->resultFileList[] = $targetFile . '/main.csv';
                    // Unlink file
                    unlink($temFile);
                } else {
                    list($type, $module, $tmp) = explode('-', basename($temFile));
                    unset($tmp);
                    if ('custom' != $mode && 'module' == $type) {
                        $targetFile             = $this->outputPath . '/' .$module . '/locale/en';
                        $this->resultFileList[] = $targetFile . '/main.csv';
                    } elseif('custom' != $mode &&'theme' == $type) {
                        $targetFile             = $this->outputPath . '/' . $module . '/locale/en';
                        $this->resultFileList[] = $targetFile . '/main.csv';
                    } else {
                        $targetFile = $this->outputPath . '/';
                        $this->resultFileList[] = $targetFile . $this->initializeData['outputName'] .'.csv';
                    }

                    Pi::service('file')->mkdir($targetFile);

                    if ('custom' != $mode) {
                        $fp = fopen($targetFile . '/main.csv', 'a');
                    } else {
                        $fp = fopen($targetFile . $this->initializeData['outputName'] . '.csv', 'a');
                    }
                    foreach ($keyWord as $value) {
                        fputcsv($fp, array($value, $value));
                    }
                    fclose($fp);
                    // Unlink file
                    unlink($temFile);
                }
            }
        }
    }

    /**
     * Get a directory all file list
     *
     * @param $dir
     * @param string $pattern filter
     * @return array file list
     */
    protected function file_list($dir, $pattern = "")
    {
        $arr = array();
        $dir_handle = opendir($dir);
        if ($dir_handle) {
            while(($file = readdir($dir_handle))!== false) {
                if($file === '.' || $file === '..' || preg_match('/^\./', $file)) {
                    continue;
                }
                $tmp = realpath($dir.'/'.$file);
                if(is_dir($tmp)) {
                    $retArr = $this->file_list($tmp,$pattern);
                    if(!empty($retArr)) {
                        $arr[] = $retArr;
                    }
                } else {
                    if($pattern === "" || preg_match($pattern,$tmp)) {
                        $arr[]            = $tmp;
                        $this->fileList[] = $tmp;
                    }
                }
            }
            closedir($dir_handle);
        }
    return $arr;
    }

    /**
     * Get directory list
     *
     * @param $dir
     * @return array
     */
    protected function getDir($dir)
    {
        $dirArray = array();
        if (false != ($handle = opendir($dir))) {
            while (false !== ($file = readdir($handle))) {
                if ($file == "." || $file == ".." || preg_match('/^\./', $file)) {
                    continue;
                }
                $tmp = realpath($dir.'/'.$file);
                if(is_dir($tmp)) {
                    $dirArray[] = $file;
                }
            }
        }
        return $dirArray;
    }

    /**
     * Filter file
     *
     * @param $file File path
     * @return bool
     */
    protected function filter($file)
    {
        if (!$this->initializeData['skip']) {
            return false;
        }

        foreach ($this->initializeData['skip'] as $filter) {
            if ($file{strlen($file) - 1} == '/') {
                $file = substr($file, 0, strlen($file) - 1);
            }
            if ($filter{strlen($filter) - 1} == '/') {
                $filter = substr($filter, 0, strlen($filter) - 1);
            }

            $filter = preg_quote($filter, '/');
            $filter = '/' . str_replace('\*', '.*', $filter) . '$/';
            if (preg_match($filter, $file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check source path according mode
     *
     * @param $mode
     * @param $source
     */
    public function checkPathStructure($mode, $source)
    {
        $baseSourcePath = Pi::path('www') . '/upload/translation/source/';
        // Check pi mode.
        if ('pi' == $mode) {
            $list = $this->getDir($baseSourcePath . $source);
            if (in_array('www', $list) && in_array('var', $list)
                && in_array('lib', $list) && in_array('usr', $list)) {
                return true;
            } else {
                return false;
            }
        }

        // Check module.
        if ('module' == $mode) {
            $list = $this->getDir($baseSourcePath . $source);
            if (in_array('src', $list) && in_array('config', $list) && in_array('template', $list)) {
                return  true;
            } else {
                return false;
            }
        }

        // Check theme.
        if ('theme' == $mode) {
            $list = $this->getDir($baseSourcePath . $source);
            if (in_array('asset', $list) && in_array('module', $list) && in_array('template', $list)) {
                return true;
            } else {
                return  false;
            }
        }
    }

    /**
     * Check path structure for one mode.
     * @return array
     */
    public function checkPathStructureAction()
    {
        Pi::service('log')->active(false);

        $mode   = $this->params('mode', '');
        $source = $this->params('source', '');
        $valid  = $this->checkPathStructure($mode, $source);

        return array(
            'valid' => $valid,
        );

    }

    /**
     * Test action
     */
    public function testAction()
    {
//        $path = '/home/liuchuang/translation-test/pi/var/data/translation/output/t2/test.csv';
//        $file = $path;

//        $file = array(
//            'source'       => $path,
//            'filename'     => 'pi-download',
//            'content_type' => 'application/octet-stream',
//        );
//        $downloader = new Download;
//        $downloader->send($file);

//        $path_to_file1 = '/home/liuchuang/translation-test/pi/var/data/translation/output/t2/test.csv';
//        $path_to_file2 = '/home/liuchuang/translation-test/pi/var/data/translation/output/t2/test2.csv';
//        $file = array(
//            $path_to_file1,
//            $path_to_file2,
//        );
//
//        $downloader = new Download;
//        $downloader->send($file);
//        $file = $path;
//
//        $downloader = new Download;
//        $downloader->send($file);

    }
}