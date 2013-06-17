<?php
/**
 *
 */
class view_admin_add_level extends view_admins {
    /**
     * @var quest
     */
    protected $_quest;

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
        $this->_title = 'Add level';
        $qid = $this->_quest->get_qid();
        $token = $this->_user->get_token();
        echo '<p><a href="?cmd=admin_levels_list&amp;qid='.$this->_quest->get_qid().'">'.$this->_quest->get_name().'</a> \ Add level</p>';
        require_once('forms/question.php');
    }
}