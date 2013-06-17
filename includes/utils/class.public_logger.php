<?php

class public_logger {
    public function __construct() {
        if (!isset($_SESSION['log']) || !is_array($_SESSION['log'])) {
            $_SESSION['log'] = array();
        }
    }

    public function push ($message) {
        $_SESSION['log'][] = (string)$message;
    }

    public function get_all() {
        return $_SESSION['log'];
    }

    public function clear() {
        unset($_SESSION['log']);
    }
}