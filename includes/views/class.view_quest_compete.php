<?php
/**
 *
 */
class view_quest_compete extends view_quests {
    /**
     * @param request $request
     * @return mixed|void
     */
    function _do_render(request $request) {
        $this->_title = 'Quest Complete | ';
        echo $this->_quest->get_outro();
    }
}