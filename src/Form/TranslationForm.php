<?php
/**
 *  Translation form
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
 * @subpackage      Form
 * @version         $Id$
 */

namespace Module\Translation\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class TranslationForm extends BaseForm
{
    public function init()
    {
        $this->add(array(
            'name'          => 'source-path',
            'attributes'    => array(
                'type'  => 'text',
            ),
        ));

        $this->add(array(
            'name'          => 'mode',
            'attributes'    => array(
                'value'   => '',
                'options' => array(
                    'pi'     => __('Pi'),
                    'module' => __('Module'),
                    'theme'  => __('Theme'),
                    'custom' => __('Custom'),
                ),
            ),
            'type'    => 'radio',
        ));

        $this->add(array(
            'name'          => 'skip-path',
            'attributes'    => array(
                'type'  => 'textarea',
                'cols'  => '90',
                'rows'  => '10',
            ),
        ));

        $this->add(array(
            'name'          => 'output-path',
            'attributes'    => array(
                'type'  => 'text',
                'value' => ''
            ),
        ));

        $this->add(array(
            'name'          => 'output-file-name',
            'attributes'    => array(
                'type'  => 'text',
                'value' => ''
            ),
        ));

        $this->add(array(
            'name'  => 'security',
            'type'  => 'csrf',
        ));

        $this->add(array(
            'name'  => 'checked-file',
            'attributes' => array(
                'id'   => 'checked-file',
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name'          => 'process',
            'attributes'    => array(
                'type'  => 'submit',
                'value' => __('Process'),
            )
        ));
    }
}
