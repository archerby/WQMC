<?php
/**
 * Basic class for all views in system. Used 'request' class to access to user data.
 *
 * @abstract
 * @author KronuS
 * @version 1.0
 */
abstract class view
{
    /**
     * Page title
     * @access protected
     * @var string
     */
    protected $_title;
    /**
     * Page menu (based on provided data about user and quest)
     * @access protected
     * @var string
     */
    protected $_navi;
    /**
     * Page content
     * @access protected
     * @var string
     */
    protected $_content;

    /**
     * User invitation message
     * @access protected
     * @var string
     */
    protected $_invitation;

    /**
     * Construct shouldn't take any parameters.
     * That is why it's final.
     * @access public
     * @final
     */
    final public function __construct() {}

    /**
     * Common method-wrapper for views display
     * @access public
     * @param request $request
     */
    public function render(request $request) {
        $this->_do_render($request);
    }

    /**
     * Prepare page template - replace variables with their values
     * @access protected
     * @param string $template_path path to the template file
     */
    protected function _prepare_template($template_path) {
		$template_path = (string)$template_path;
		if (!is_file($template_path)) {
			echo 'Invalid path - '.$template_path;
			return;
		}
		$title = $this->_title;
		$content = $this->_content;
		$navi = $this->_navi;
        $invitation = $this->_invitation;
        $log = $this->_prepare_log_block();
		require_once($template_path);
	}

    protected function _prepare_log_block() {
        $logger = new public_logger();
        $r = '';
        $logs = $logger->get_all();
        if(count($logs)) {
            $r = '<ul class="log">';
            foreach ($_SESSION['log'] as $log) {
                $r .= '<li>'.$log.'</li>';
            }
            $r .= '</ul>';
            $logger->clear();
        }
        return $r;
    }

    /**
     * Main method for child classes. Main child views logic should be here
     * @param request $request
     * @return mixed
     */
    abstract function _do_render(request $request);
}
