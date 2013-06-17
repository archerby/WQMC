<?php
/**
 *
 */
class view_admin_levels_list extends view_admins {
    /**
     * @var
     */
    protected $_quest;

    /**
     *
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
        if (is_null($this->_user) || is_null($this->_quest)) {
            echo __CLASS__.' user or quest not set.';
            return;
        }
        $this->_title = 'Quest levels';
        echo '<h1>'.$this->_quest->get_name().'</h1>';
        if (count($this->_quest->get_levels()) > 0 && $this->_user->get_permission('can_add_level')) {
            echo '<p><a href="?cmd=admin_add_level&amp;qid='.$this->_quest->get_qid().'">Add level</a></p>';
        }
        echo '<table>';
        echo '<thead><tr><td>OrderId</td><td>Task</td><td>Answer</td><td></td><td></td></tr></thead><tbody>';
        foreach($this->_quest->get_levels() as $level) {
            echo '<tr>';
            echo '<td>'.$level->get_oid().'</td>';
            echo '<td>'.$level->get_short_task().' ...</td>';
            echo '<td>'.$level->get_answer().'</td>';
            echo '<td><a href="?cmd=admin_update_level&amp;lid='.$level->get_lid().'">Edit</a></td>';
            if ($this->_user->get_permission('can_delete_level')) {
                echo '<td><a onclick="return confirm(\'Realy do this?\');" href="?cmd=admin_delete_level&amp;lid='.$level->get_lid().'">Delete</a></td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
        if ($this->_user->get_permission('can_add_level')) {
            echo '<p><a href="?cmd=admin_add_level&amp;qid='.$this->_quest->get_qid().'">Add level</a></p>';
        }
    }
}