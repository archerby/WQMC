<?php

class view_quest_winners extends view_quests {
    public function set_quest(quest $quest) {
        $this->_quest = $quest;
        return $this;
    }
    function _do_render(request $request) {
        $this->_title = 'Winners | ';
        $winners = $this->_quest->get_winners();
        echo '<h1>Winner list | <a href="?cmd=quest&amp;qid='.$this->_quest->get_qid().'">'.$this->_quest->get_name().'</a></h1>';
        if (count($winners) > 0) {
            $i = 1;
            echo '<table>';
            foreach($winners as $winner) {
                echo '<tr class="'.($i%2?'odd':'even').'"><td>'.$i.'</td><td>'.$winner['name'].'</td><td>'.$winner['complete_timestamp'].'</td></tr>';
                $i++;
            }
            echo '</table>';
        }
        else {
            echo '<p>Nobody finished that quest.</p>';
        }
    }
}