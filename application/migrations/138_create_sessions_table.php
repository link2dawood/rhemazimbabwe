<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_sessions_table extends CI_Migration {

    public function up()
    {
        // Create sessions table for database session storage
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => FALSE
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => FALSE
            ),
            'timestamp' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
                'default' => 0,
                'null' => FALSE
            ),
            'data' => array(
                'type' => 'BLOB',
                'null' => FALSE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('timestamp');
        $this->dbforge->create_table('ci_sessions', TRUE, array('ENGINE' => 'InnoDB'));

        echo "Sessions table created successfully!\n";
    }

    public function down()
    {
        $this->dbforge->drop_table('ci_sessions', TRUE);
        echo "Sessions table dropped successfully!\n";
    }
}
