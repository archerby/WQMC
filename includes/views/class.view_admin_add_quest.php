<?php

/**
 *
 */
class view_admin_add_quest extends view_admins {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_render(request $request) {
        $this->_title = 'Add quest';
        $token = $this->_user->get_token();
        require_once('forms/quest.php');
    }
}