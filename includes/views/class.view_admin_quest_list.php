<?php

class view_admin_quest_list extends view_admins {
    protected $_quests;
    public function set_quests(array $quests) {
        $this->_quests = $quests;
        return $this;
    }
    function _do_render(request $request) {
        if (is_null($this->_quests) || is_null($this->_user)) {
            echo __CLASS__.' quests or user not set.';
            return;
        }
        $this->_title = 'Quests list';
        echo '<h1>Quests</h1>';
        echo '<table>';
        foreach($this->_quests as $quest) {
            echo '<tr>';
            echo '<td><a href="?cmd=admin_levels_list&qid='.$quest->get_qid().'">'.$quest->get_name().'</a></td>';
            if ($this->_user->get_permission('can_update_quest')) {
                echo '<td><a href="?cmd=admin_update_quest&qid='.$quest->get_qid().'">Edit</a></td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        if ($this->_user->get_permission('can_add_quest')) {
            echo '<p><a href="?cmd=admin_add_quest">Add quest</a></p>';
        }
    }
}