<?php
/**
 * User level view
 *
 * @author KronuS
 * @version 1.0
 */
class view_level extends view_quests
{
    /**
     * Current user level
     * @access private
     * @var question
     */
    private $_level;

    /**
     * Set current user level
     * @param question $level
     * @return view_level
     */
    public function set_level(question $level) {
        $this->_level = $level;
        return $this;
    }

    /**
     * @param request $request
     * @return mixed|void
     */
    function _do_render(request $request) {
        if (is_null($this->_level)) {
            return;
        }
        echo $this->_level->get_task();
        if (!is_null($request->get('answer'))) {
            // if answer exists, its 100% wrong, because correct answer is processed in controller
            echo '<p class="alert">Wrong answer!</p>';
        }
        $qid = $this->_level->get_qid();
        require_once('forms/answer.php');
    }
}
