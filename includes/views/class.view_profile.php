<?php

/**
 * User stats view
 *
 * @author KronuS
 * @version 1.0
 */
class view_profile extends view
{
    /**
     * User
     * @access private
     * @var user
     */
    private $_user;

    /**
     * Set current user
     * @param user $user
     * @return view_profile
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
            return;
        }

        $this->_title = 'Quests stats';
        ob_start();
        $stats = $this->_user->get_stats();
        if (count($stats) > 0) {
            echo '<table>';
            foreach ($stats as $qid=>$stat) {
                echo '<tr class="subh1"><td colspan="2"><a href="?cmd=quest&amp;qid='.$qid.'">'.$stat['name'].'</a></td></tr>';
                unset($stat['name']);
                $i = 1;
                if (count($stat) > 0) {
                    echo '<tr class="subh2"><td>Level</td><td>Complete date</td></tr>';
                    foreach ($stat as $lid=>$date) {
                        echo '<tr><td>'.$i.'</td><td>'.$date.'</td></tr>';
                        $i++;
                    }
                }
            }
            echo '</table>';
        }
        $this->_content = ob_get_clean();
        ob_start();
        $view_navi = new view_navi();
        $view_navi->set_user($this->_user)->render($request);
        $this->_navi = ob_get_clean();
        $this->_invitation = 'Hello, <a href="?cmd=profile">'.$this->_user->get_login().'</a>';
        $this->_prepare_template(config::get('default_theme_path').'template.php');
    }
}
