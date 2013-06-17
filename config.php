<?php

error_reporting(E_ALL);

session_set_cookie_params(0, '/', '', false, true);

session_start();

define('SYSTEM', true);

/**
 * Basic class with system's configuration data
 * @author KronuS
 * @version 1.00
 * @date 24.10.2012
 */
class config {
    /**
     * Configuration data
     * @access private
     * @static
     * @var array
     */
    private static $_data = array(
        'db_user' => 'root',
        'db_pass' => 'KronuS',
        'db_host' => 'localhost',
        'db_name' => 'kqc2',
        'db_table_prefix' => 'tt_'
    );

    /**
     * Private construct to avoid object initializing
     * @access private
     */
    private function __construct() {}
    public static function init() {
        self::$_data['base_path'] = dirname(__FILE__);
        self::$_data['themes_path'] = self::$_data['base_path'].DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR;
        self::$_data['uploads_path'] = str_replace(self::$_data['base_path'], '', self::$_data['base_path'].DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR);
		self::$_data['default_theme_path'] = self::$_data['themes_path'].'default'.DIRECTORY_SEPARATOR;
        self::$_data['admin_theme_path'] = self::$_data['themes_path'].'default'.DIRECTORY_SEPARATOR;
        $db = db::obtain(self::get('db_host'), self::get('db_user'), self::get('db_pass'), self::get('db_name'), self::get('db_table_prefix'));
        $db->connect_pdo();
    }
    /**
     * Get configuration parameter by key
     * @param string $key data-array key
     * @return null
     */
    public static function get($key) {
        if(isset(self::$_data[$key])) {
            return self::$_data[$key];
        }
        return null;
    }
}

config::init();

function __autoload($class) {
    //echo $class.'<br />';
    scan(config::get('base_path'), $class);
}

function scan($path = '.', $class) {
    $ignore = array('.', '..');
    $dh = opendir($path);
    while(false !== ($file = readdir($dh))){
        if(!in_array($file, $ignore)) {
            if(is_dir($path.DIRECTORY_SEPARATOR.$file)) {
                scan($path.DIRECTORY_SEPARATOR.$file, $class);
            }
            else {
                if ($file === 'class.'.$class.'.php') {
                    require_once($path.DIRECTORY_SEPARATOR.$file);
                    return;
                }
            }
        }
    }
    closedir($dh);
}