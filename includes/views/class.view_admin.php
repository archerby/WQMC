<?php

class view_admin extends view_admins {
    function _do_render(request $request) {
        $this->_title = 'Admin';
    }
}