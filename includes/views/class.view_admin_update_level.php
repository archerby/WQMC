<?php
/**
 *
 */
class view_admin_update_level extends view_admins {
    /**
     * @var quest
     */
    protected $_quest;

    /**
     * @var question
     */
    protected $_level;

    /**
     * @param question $level
     * @return view_admin_update_level
     */
    public function set_level(question $level) {
        $this->_level = $level;
        return $this;
    }

    /**
     * @param quest $quest
     * @return view_admin_add_level
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
        $this->_title = 'Update level';
        $data = $this->_level->fields_to_array();
        $token = $this->_user->get_token();
        echo '<p><a href="?cmd=admin_levels_list&amp;qid='.$this->_quest->get_qid().'">'.$this->_quest->get_name().'</a> \ Edit level</p>';
        require_once('forms/question.php');
    }
}