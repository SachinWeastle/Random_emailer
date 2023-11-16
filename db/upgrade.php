<?php

function xmldb_local_randomemail_upgrade($oldversion){
	global $DB;
	$dbman = $DB->get_manager();
	
	if($oldversion < 2023111100 ){
	    
	    $table = new xmldb_table('local_randomemail_users');
	    
	    $table->add_field('id', XMLDB_TYPE_INTEGER, '20', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
	    $table->add_field('firstname', XMLDB_TYPE_TEXT, 'big', null, XMLDB_NOTNULL, null, '');
	    $table->add_field('lastname', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
	    $table->add_field('email', XMLDB_TYPE_TEXT, 'big', null, XMLDB_NOTNULL, null, '');
		$table->add_field('mailsent', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
	    $table->add_field('createdtime', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
	    $table->add_field('updatedtime', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
	    
	    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
	    
	    if (!$dbman->table_exists( $table )) {
	        $dbman->create_table( $table );
	    }
	    
	    upgrade_plugin_savepoint( true, 2023111100, 'local', 'randomemail' );
	}

	return true;
}