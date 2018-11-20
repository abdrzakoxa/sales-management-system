<?php

$themes = ['light','dark','purple','blue'];
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'blue';
if (!in_array($theme,$themes)){
    $theme = 'blue';
}
$theme = CSS_PATH . DS .'themes/'. $theme .'.css';

return [
    'structure' => [
        'starthead' => STRUCTURE_PATH . DS . 'starthead.php',
        'sourceshead' => STRUCTURE_PATH . DS . 'sourceshead.php',
        'endhead' => STRUCTURE_PATH . DS . 'endhead.php',
        'startcontent' => STRUCTURE_PATH . DS . 'startcontent.php',
        'navigation' => STRUCTURE_PATH . DS . 'navigation.php',
        ':VIEW' => 'here include view ',
        'endcontent' => STRUCTURE_PATH . DS . 'endcontent.php',
        'sourcesfooter' => STRUCTURE_PATH . DS . 'sourcesfooter.php',
        'endfooter' => STRUCTURE_PATH . DS . 'endfooter.php',
    ],
    'header' => [
        /** here write all sources in css*/
        'css' => [
            'fontAwesome' =>  LIBRARIES_CSS_PATH .DS .'fontawesome.css',
            'normalize' =>  PLUGINS_CSS_PATH .DS .'normalize.css',

            '_sales/default'=>[
                'data-table' =>  PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                'data-table-edit' =>  CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_purchases/default'=>[
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_products/default'=>[
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
         '_users'=>[
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
                CSS_PATH . DS .'users.css'
            ],

            '~auth' => [
                CSS_PATH . DS .'main.css',
                ],
            '_settings' => CSS_PATH . DS .'settings.css',
            '_install' => CSS_PATH . DS .'install.css',
            '_sales/preview' => CSS_PATH . DS .'invoicepreview.css',
            '_products/preview' => CSS_PATH . DS .'productspreview.css',
            '_dashboard' => [
                PLUGINS_CSS_PATH . DS . 'pignose.calendar.min.css',
                CSS_PATH . DS .'index.css',
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_auth' => CSS_PATH . DS .'auth.css',
            '_permissions' => [
                CSS_PATH . DS .'permissions.css',
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
                ],
            '_Expenses' => [
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_Suppliers' => [
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_settings/backupdatabase' => [
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_settings/units' => [
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],

            '_Clients' => [
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '_productscategories' => CSS_PATH . DS .'productscategories.css',
            '_expensescategories' => CSS_PATH . DS .'expensescategories.css',
            '_notifications' => CSS_PATH . DS .'notifications.css',
            '_notfound' => CSS_PATH . DS .'permissions.css',
            '_profile' => PLUGINS_CSS_PATH . DS .'croppie.css',
            '_groups' => [
                CSS_PATH . DS .'groups.css',
                PLUGINS_CSS_PATH . DS . 'DataTables.min.css',
                CSS_PATH . DS . "dataTablesEditeCss.css",
            ],
            '-ar' => [
                CSS_PATH . DS .'lang.css',
                CSS_PATH . DS .'inputborder_rtl.css',
                ],

            '-en' => [
                CSS_PATH . DS .'inputborder_ltr.css',
            ],
            'themes' => $theme,


        ],
        'js' => [

        ],
    ],
    'footer' => [
        'jquery' =>  LIBRARIES_JS_PATH .DS .'jquery.js',
        '_profile' => PLUGINS_JS_PATH . DS . 'cropit.js',
        '~sales/invoice' => PLUGINS_JS_PATH . DS . 'DataTables.min.js',
        'validinputs' => JS_PATH . DS . 'validinputs.js',
        '_dashboard' =>  [
            PLUGINS_JS_PATH .DS .'Chart.min.js',
            JS_PATH .DS .'chart.js',
            PLUGINS_JS_PATH . DS . 'pignose.calendar.full.min.js',
        ],
        '_products/preview' =>  PLUGINS_JS_PATH .DS .'JsBarcode.all.min.js',
        'main' =>  JS_PATH .DS .'main.js',
    ]
]



?>

