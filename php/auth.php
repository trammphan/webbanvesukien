<?php
function is_logged_in(): bool {
    return isset($_COOKIE['email']) && $_COOKIE['email'] !== '';
}


