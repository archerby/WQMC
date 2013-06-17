<?php

abstract class view_quests extends view
{
    protected $_quest;
    protected $_user;
    public function set_user(user $user) {
        $this->_user = $user;
        return $this;
    }
    public function set_quest(quest $quest) {
        $this->_quest = $quest;
        return $this;
    }
    public function render(request $request) {
        if (is_null($this->_quest) || is_null($this->_user)) {
            echo __CLASS__.' user or quest are not set.';
            return;
        }
        // template variables
        ob_start();
        $this->_do_render($request);
        $this->_content = ob_get_clean();
        $this->_title .= ' '.$this->_quest->get_name();
		ob_start();
        $navi_view = new view_navi();
        $navi_view->set_quest($this->_quest)->set_user($this->_user)->render($request);
        $this->_navi = ob_get_clean();
        $this->_invitation = 'Hello, '.($this->_user->get_login()?'<a href="?cmd=profile">'.$this->_user->get_login().'</a>':'guest');
        // including template
		$this->_prepare_template(config::get('themes_path').$this->_quest->get_theme().DIRECTORY_SEPARATOR.'template.php');
    }

}