<?php
/**
 * Main application class. Used like front-controller.
 * Instance created only on index page.
 *
 * @uses command
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class app {
    /**
     * Base command class. All commands should extend it
     * @access private
     * @static
     * @var ReflectionClass
     */
    private static $_base_cmd;

    /**
     * Default command class. Extends command class. If valid command not provided, this command is executed
     * @access private
     * @static
     * @var ReflectionClass
     */
    private static $_default_cmd;

    /**
     * Array with installed themes
     * @access private
     * @static
     * @var array
     */
    private static $_themes_list;

    /**
     * Initializing default object settings
     */
    public function __construct() {
        self::$_base_cmd = new ReflectionClass('command');
        self::$_default_cmd = new ReflectionClass('command_quest_list');
    }

    /**
     * Check all request data to find and execute command. If command isn't found, execute default command
     * @param request $request
     * @return command
     */
    public function handle_request(request $request) {
        $command = $request->get('cmd');
        if (!$command) {
            return self::$_default_cmd->newInstance();
        }
        $command = str_replace(array('.', DIRECTORY_SEPARATOR), '', $command);
        $filepath = config::get('base_path').DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'commands'.DIRECTORY_SEPARATOR.'class.command_'.$command.'.php';
        if (file_exists($filepath)) {
            require_once($filepath);
            $classname = 'command_'.$command;
            if (class_exists($classname)) {
                $cmd_class = new ReflectionClass($classname);
                if ($cmd_class->isSubclassOf(self::$_base_cmd)) {
                    return $cmd_class->newInstance();
                }
            }
			else {
				self::redirect('?');
			}
        }
		else {
			self::redirect('?');
		}
        return null;
    }

    /**
     * Redirect to provided url
     * @access public
     * @static
     * @param string $url
     */
    public static function redirect($url) {
        $url = (string)$url;
        header('Location: '.$url);
        echo 'Go to <a href="'.$url.'">'.$url.'</a>.';
        exit;
    }

    public static function get_themes() {
        if (!is_array(self::$_themes_list)) {
            self::_load_themes();
        }
        return self::$_themes_list;
    }

    private static function _load_themes() {
        $themes_dir = config::get('themes_path');
        $dirs = scandir($themes_dir);
        foreach($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }
            if (file_exists($themes_dir.$dir.DIRECTORY_SEPARATOR.'template.php')) {
                self::$_themes_list[$dir] = $dir;
            }
        }
    }
}