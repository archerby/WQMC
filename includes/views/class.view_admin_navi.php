<?php

class view_admin_navi extends view {
    /**
     * @var user
     */
    protected $_user;

    /**
     * @param user $user
     * @return view_admin_navi
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
            echo __CLASS__.' user is not set.';
            return;
        }
        $perms = $this->_user->get_permissions();
        echo '<ul>';
        if ($perms['can_add_quest'] || $perms['can_update_quest'] || $perms['can_delete_quest']) {
            echo '<li><a href="?cmd=admin_quest_list">Quests</a></li>';
        }
        if ($perms['can_upload_file'] || $perms['can_delete_file']) {
            echo '<li><a href="?cmd=admin_files">Files</a></li>';
        }
        echo '<li><a href="?cmd=logout&amp;token='.$this->_user->get_token().'">Logout</a></li>';
        echo '</ul>';
    }
}