<?php

defined('MOODLE_INTERNAL') || die();

// Admin settings
$ADMIN->add('users', new admin_category('randomemail', new lang_string('randommailtousers', 'local_randomemail')));

$ADMIN->add('randomemail', new admin_externalpage('uploadrandomusers', get_string('uploadusers', 'local_randomemail'), "$CFG->wwwroot/local/randomemail/upload_csv.php", 'local/randomemail:allowupload'));
$ADMIN->add('randomemail', new admin_externalpage('viewrandomusers', get_string('viewprocessedusers','local_randomemail'), "$CFG->wwwroot/local/randomemail/view_processed_users.php", 'moodle/site:config'));