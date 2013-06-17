<?php

class view_start_quest extends view_quests
{
    function _do_render(request $request) {
        if (is_null($this->_quest)) {
            return;
        }
        echo $this->_quest->get_intro();
        echo '<p><a href="?cmd=start_quest&qid='.$this->_quest->get_qid().'">Start quest</a></p>';
    }
}