<?php
/**
 * Translation module module config
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
 * @author          Chuang Liu <liuchuang@eefocus.com>
 * @since           1.0
 * @package         Module\Translation
 */

return array(
    'meta'         => array(
        'title'         => __('Translation'),
        'description'   => __('Translation module'),
        'version'       => '1.0.0',
        'license'       => 'New BSD',
        'logo'          => 'image/logo.png',
        'clonable'      => false,
    ),
    'author'       => array(
        'name'          => 'Liu Chuang',
        'email'         => 'liuchuang@eefocus.com',
        'website'       => 'http://www.eefocus.com',
        'credits'       => 'EEFOCUS Team.',
    ),
    'dependency'   => array(
    ),
    'maintenance'  => array(
        'resource'      => array(
            'config'     => 'config.php',
            'navigation'    => 'navigation.php',
        ),
    ),
);
