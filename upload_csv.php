<?php

require_once(dirname( __FILE__ ) . '/../../config.php');
require_once(dirname( __FILE__ ) . '/upload_csv_form.php');
require_once(dirname( __FILE__ ) . '/locallib.php');

require_login(0, false);

global $CFG, $USER, $PAGE, $DB;

$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->navbar->add(get_string('uploadusers', 'local_randomemail'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('uploadusers', 'local_randomemail'));
$PAGE->set_url('/local/randomemail/upload_csv.php');
$PAGE->set_heading(get_string('uploadusers', 'local_randomemail'));

if(!has_capability('local/randomemail:allowupload', $PAGE->context)){
    print_error(get_string('noaccess', 'local_randomemail'));
}

$mform = new upload_csv_form();

if ($fromform = $mform->get_data()) {
    
    $realfilename = $mform->get_new_filename('uploadedfile');
    $importfile = "{$CFG->tempdir}/csvfile/{$realfilename}";
    make_temp_directory('csvfile');
    
    $mform->save_file('uploadedfile', $importfile, true);

    $open_csv_file = $CFG->dataroot."/temp/csvfile/".$realfilename;
    
    $table = upload_random_email_file($open_csv_file);

    echo $OUTPUT->header();

    echo $table;

    echo $OUTPUT->footer();
} else {
    echo $OUTPUT->header();

    $mform->display();

    echo $OUTPUT->footer();
}