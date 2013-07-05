<?php
/**
 * Created by JetBrains PhpStorm.
 * User: liu
 * Date: 13-6-9
 * Time: 上午9:47
 * To change this template use File | Settings | File Templates.
 */

$config = array();
$config['category'] = array(
    array(
        'name'    => 'general',
        'title'   => 'General',
    ),
);
$config['item']     = array(
    'pi_mode_skip_path'   => array(
        'category'       => 'general',
        'title'          => 'Pi mode skip path',
        'description'    => 'Pi mode skip path',
        'value'          => <<<EOD
doc
usr/loacle
usr/module/*/asset
usr/module/*/locale
usr/module/*/sql
usr/theme/default/asset
usr/theme/default/locale
usr/theme/*/asset
usr/theme/*/locale
var
www
EOD
,
            'edit'          => 'textarea',
    ),

    'module_mode_skip_path'    => array(
        'category'       => 'general',
        'title'          => 'Module mode skip path',
        'description'    => 'Module mode skip path',
        'value'          => <<<EOD
asset
loacle
sql
EOD
,
        'edit'          => 'textarea',
    ),

    'theme_mode_skip_path'    => array(
        'category'       => 'general',
        'title'          => 'Theme mode skip path',
        'description'    => 'Theme mode skip path',
        'value'          => <<<EOD
asset
loacle
EOD
,
        'edit'          => 'textarea',
    ),
);

return $config;