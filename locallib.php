<?php

require_once(dirname( __FILE__ ) . '/../../config.php');
require_once(dirname( __FILE__ ) . '/../../lib/phpexcel/PHPExcel.php');
require_once(dirname( __FILE__ ) . '/../../lib/filelib.php');

function upload_random_email_file($csv_file) {

    $open_csv_file = fopen($csv_file, 'r');

    $pageurl = new moodle_url('/local/randomemail/upload_csv.php', array('error' => '1'));

    if ($open_csv_file !== false) {
        // Required column validation
        $cuurent_headers = fgetcsv($open_csv_file);

        $required_headers = array('firstname', 'lastname', 'email');
        
        $missing_headers = array_diff($required_headers, $cuurent_headers);

        if (!empty($missing_headers)) {
            redirect($pageurl, get_string('requiredheader', 'local_randomemail') . implode(', ', $required_headers), 2);
        }

        $random_email_user_list = array();
        $table_rows = "";
    
        while (($row = fgetcsv($open_csv_file)) !== false) {
            $data = array();

            $first_name = trim($row[0]);
            $last_name = trim($row[1]);
            $email_id = trim($row[2]);

            // Empty field validation
            if(empty($first_name) || empty($last_name) || empty($email_id)) {
                redirect($pageurl, get_string('emptyfields', 'local_randomemail'), 2);
            }

            // Email validation
            if(filter_var($email_id, FILTER_VALIDATE_EMAIL) === false) {
                redirect($pageurl, get_string('invalidemail', 'local_randomemail'), 2);
            }

            $data['firstname'] = $first_name;
            $data['lastname'] = $last_name;
            $data['email'] = $email_id;

            $table_rows .= "<tr>
            <td class='cell c0'>$first_name</td>
            <td class='cell c1'>$last_name</td>
            <td class='cell c2'>$email_id</td>
            </tr>";

            $random_email_user_list[] = $data;
        }

        fclose($open_csv_file);

        $table = "<table class='generaltable'>
                    <thead>
                    <tr>
                    <th class='header c0'><b>firstname</b></th>
                    <th class='header c1'><b>lastname</b></th>
                    <th class='header c2'><b>email</b></th>
                    </tr>
                    </thead>";

        $table .= $table_rows . "</table>";
        
        add_users_to_email_queue($random_email_user_list);

        return $table;
    } else {
        redirect($pageurl, get_string('fileerror', 'local_randomemail'), 2);
    }
}

function add_users_to_email_queue($queue_users) {
    global $CFG, $DB, $USER;

    foreach($queue_users as $user_data) {
        $sql = 'select * from {local_randomemail_users} where email = ? and mailsent = ?';
        if (!$DB->record_exists_sql($sql, array($user_data['email'], 0))) {
            $queue_record = new stdClass();

            $queue_record->firstname = $user_data['firstname'];
            $queue_record->lastname = $user_data['lastname'];
            $queue_record->email = $user_data['email'];
            $queue_record->mailsent = 0;
            $queue_record->createdtime = time();
            $queue_record->updatedtime = null;

            $DB->insert_record('local_randomemail_users', $queue_record);
        }
    }
}


function get_base_mail_obj(){
    global $CFG;

    $mail = new PHPMailer;
    $mail->isSMTP();
    
    $host_port = explode(':', $CFG->smtphosts);
    $mail->Host = $host_port[0];
    $mail->Port = $host_port[1];
    $mail->SMTPAuth = true;
    $mail->Username = $CFG->smtpuser;
    $mail->Password = $CFG->smtppass;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom($CFG->noreplyaddress, $CFG->supportname);
    $mail->isHTML(true);
    $mail->AltBody = 'Please use HTML supported mailing client';

    return $mail;
}

function send_random_mail($mail){
    if(!$mail->send()) {
        return false;
    }else{
       return true;
    }
}