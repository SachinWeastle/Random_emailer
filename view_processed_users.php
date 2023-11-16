<?php

require_once(dirname( __FILE__ ) . '/../../config.php');

require_login(0, false);

global $OUTPUT, $PAGE, $DB;

$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->navbar->add(get_string('viewprocessedusers', 'local_randomemail'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('viewprocessedusers', 'local_randomemail'));
$PAGE->set_url('/local/randomemail/view_processed_users.php');
$PAGE->set_heading(get_string('viewprocessedusers', 'local_randomemail'));

echo $OUTPUT->header();

if (!is_siteadmin()) {
    print_error(get_string('noaccess', 'local_randomemail'));
}

$sql = 'select * from {local_randomemail_users} where mailsent = ? order by updatedtime desc';
$processed_users = $DB->get_records_sql($sql, array(1));

$template_data = array();
$index = 0;
$user_exists = false;

foreach($processed_users as $user_data) {
    $template_data[$index] = new stdClass();
    $template_data[$index]->slno = $index + 1;
    $template_data[$index]->username = $user_data->firstname . ' ' . $user_data->lastname;
    $template_data[$index]->email = $user_data->email;
    $template_data[$index]->datesent = date('Y-m-d h:i a', $user_data->updatedtime);

    $index++;
}

if($index) {
    $user_exists = true;
}

echo $OUTPUT->render_from_template("local_randomemail/view_processed_users", array('userlist' => $template_data, 'userexists' => $user_exists));

echo $OUTPUT->footer();