<?php
/**
 *
 */
class view_admin_update_quest extends view_admins {
    /**
     * @var quest
     */
    private $_quest;

    /**
     * @param quest $quest
     * @return view_admin_update_quest
     */
    public function set_quest(quest $quest) {
        $this->_quest = $quest;
        return $this;
    }
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_render(request $request) {
        $this->_title = 'Edit quest';
        $data = $this->_quest->fields_to_array();
        $token = $this->_user->get_token();
        require_once('forms/quest.php');
    }
}