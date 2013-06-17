<?php
/**
 *
 */
abstract class view_admins extends view {
    /**
     * @var user
     */
    protected $_user;

    /**
     *
     */
    public function set_user(user $user) {
        $this->_user = $user;
        return $this;
    }
    /**
     * @param request $request
     */
    public function render(request $request) {
        // template variables
        ob_start();
        $this->_do_render($request);
        $this->_content = ob_get_clean();
        $this->_title  = $this->_title.' | Admin';
        ob_start();
        $navi_view = new view_admin_navi();
        $navi_view->set_user($this->_user)->render($request);
        $this->_navi = ob_get_clean();
        // including template
        $this->_prepare_template(config::get('admin_theme_path').DIRECTORY_SEPARATOR.'template.php');
    }
}