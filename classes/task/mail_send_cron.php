<?php

namespace local_randomemail\task;

class mail_send_cron extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('mail_send_cron', 'local_randomemail');
    }
    
    public function execute() {
        mtrace('Send random mail to queued users');
        require_once 'send_random_mail.php';
    }
}