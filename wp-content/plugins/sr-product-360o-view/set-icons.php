<?php

$sr_360_icon_1 = intval(sanitize_option('sr_360_icon_1', get_option('sr_360_icon_1')));
$sr_360_icon_2 = intval(sanitize_option('sr_360_icon_2', get_option('sr_360_icon_2')));

if(!$sr_360_icon_1 || !$sr_360_icon_1) {
    add_action('admin_init', array($SR_WC_P360V, 'sr_set_360_icons'));
}