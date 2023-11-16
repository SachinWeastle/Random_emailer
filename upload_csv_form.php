<?php

defined('MOODLE_INTERNAL') || die();

require_once(dirname( __FILE__ ) . '/../../lib/formslib.php');

class upload_csv_form extends moodleform {
    
    protected function definition() {
        global $CFG;
        
        $mform = &$this->_form;
        
        $mform->addElement('filepicker', 'uploadedfile', get_string('uploadedfile', 'local_randomemail'), null, array('accepted_types' => array('.csv')) );
        
        $mform->addRule('uploadedfile', null, 'required', null, 'client');

        $mform->addElement('submit', 'submitbutton', get_string('upload', 'local_randomemail'));
    }        
    
    public function validation($data, $files) { 
        $errors = array();
        return $errors;
    }
}