<?php

/**
 * Basic class for all commands in system. Used 'request' class to access to user data.
 *
 * @abstract
 * @author KronuS
 * @version 1.0
 */
abstract class command {
    /**
     * @access protected
     * @var public_logger
     */
    protected $_logger;
    /**
     * Construct shouldn't take any parameters (in this case provided easy command creating).
     * That is why it's final.
     * @access public
     * @final
     */
    final public function __construct() {
        $this->_logger = new public_logger();
    }

    /**
     * Common method-wrapper for commands execution
     * @access public
     * @param request $request
     */
    public function execute(request $request) {
		$this->_do_execute($request);
	}

    /**
     * Main method for child classes. All code should be here
     * @abstract
     * @param request $request
     * @return mixed
     */
    abstract function _do_execute(request $request);
}