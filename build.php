<?php
/**
 * @author    Florian Bender <florian.bender@payone.de>
 * @version   1.0
 * @copyright 2014-2016 PAYONE GmbH
 * @desc      Build description file for astorm/MagentoTarToConnect
 */

return array(
'base_dir'               => '/home/travis/build/fjbender/magento-1',
'archive_files'          => 'package.tar',

'extension_name'         => 'Mage_Payone',
// This will get replaced by Travis
'extension_version'      => '%%VERSION%%',
'skip_version_compare'   => true, 
'auto_detect_version'   => false,
// Travis build path
'path_output'            => '/home/travis/build/fjbender/magento-1',
'stability'              => 'stable',
'license'                => 'Open Software License (OSL)',
'channel'                => 'community',
'summary'                => 'PAYONE Payment for Magento: One partner. One contract. One payment.',
'description'            => 'For a detailed description, please see https://github.com/PAYONE-GmbH/magento-1/',
'notes'                  => 'Mage_Payone-%%VERSION%%',
'author_name'            => 'PAYONE',
'author_user'            => 'jgerle',
'author_email'           => 'magento@payone.de',
'php_min'                => '5.2.0',
'php_max'                => '7.2.0'
);
