<?php

class view_quest_stats extends view_quests {
    public function set_quest(quest $quest) {
        $this->_quest = $quest;
        return $this;
    }
    function _do_render(request $request) {
        $this->_title = 'Users | ';
        $stats = $this->_quest->get_users();
        echo '<h1>Users | <a href="?cmd=quest&amp;qid='.$this->_quest->get_qid().'">'.$this->_quest->get_name().'</a></h1>';
        if (count($stats) > 0) {
            $i = 1;

            $win_count = 0;
            $sum = 0;
            if (isset($stats[-1])) {
                $win_count = $stats[-1];
                $sum += $win_count;
                unset($stats[-1]);
            }
            echo '<table class="users">';
            echo '<thead><tr><td>Level</td><td>Users count</td></tr></thead><tbody>';
            foreach($stats as $l => $count) {
                echo '<tr class="'.($i%2?'odd':'even').'"><td>'.(($l===-1)?'Finish':$l).'</td><td>'.$count.'</td></tr>';
                $sum += $count;
                $i++;
            }
            echo '<tr class="'.($i%2?'odd':'even').'"><td>Finish</td><td>'.$win_count.'</td></tr>';
            echo '</tbody></table>';
            echo '<p>Total: '.$sum.' users.</p>';
        }
        else {
            echo '<p>Nobody started that quest.</p>';
        }
    }
}