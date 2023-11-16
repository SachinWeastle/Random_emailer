<?php

define('CLI_SCRIPT', true);

require_once __DIR__ . "/../../../../config.php";
require_once(dirname( __FILE__ ) . '/../../locallib.php');

global $DB;

$sql = "select * from {local_randomemail_users} where mailsent = 0";
$queued_user_list = $DB->get_records_sql($sql);

$mail = get_base_mail_obj();

$from_user = $DB->get_record_sql("select id, concat(firstname,' ',lastname) as name, email from {user} where id = 2");
$mail->addReplyTo($from_user->email, $from_user->name);

$subject_str = 'It\'s your random mail';
$body = 'Hi,<br><br> You have received a random email.<br>
'.$CFG->wwwroot.'<br><br>Regards,<br>'.$from_user->name;

$mail->Subject = $subject_str;
$mail->Body = $body;

foreach($queued_user_list as $user_data) {
    $user_name = $user_data->firstname . ' ' . $user_data->lastname; 

    $mail->addAddress($user_data->email, $user_name);

    $isSent = send_random_mail($mail);

    if($isSent) {
        $user_data->mailsent = 1;
        $user_data->updatedtime = time();

        $DB->update_record('local_randomemail_users', $user_data);
    }
}