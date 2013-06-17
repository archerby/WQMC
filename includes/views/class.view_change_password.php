<?php
/**
 * User level view
 *
 * @author KronuS
 * @version 1.0
 */
class view_change_password extends view
{
    /**
     * User
     * @access private
     * @var user
     */
    private $_user;

    /**
     * Set current user
     * @param user $user
     * @return view_change_password
     */
    public function set_user(user $user) {
        $this->_user = $user;
        return $this;
    }

    /**
     * @param request $request
     * @return mixed|void
     */
    function _do_render(request $request) {
        if (is_null($this->_user)) {
            return;
        }

        $this->_title = 'Change password';
        ob_start();
        $token = $this->_user->get_token();
        require_once('forms/password.php');
        $this->_content = ob_get_clean();
        ob_start();
        $view_navi = new view_navi();
        $view_navi->set_user($this->_user)->render($request);
        $this->_navi = ob_get_clean();
        $this->_invitation = 'Hello, '.($this->_user->get_login()?'<a href="?cmd=profile">'.$this->_user->get_login().'</a>':'guest');
        $this->_prepare_template(config::get('default_theme_path').'template.php');
    }
}
