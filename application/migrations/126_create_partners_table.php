<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partners_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'partner_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE
            ),
            'student_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'firstname' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ),
            'lastname' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE
            ),
            'mobileno' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'address' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'city' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'state' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'country' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'zipcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'giving_frequency_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'giving_type_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'contribution_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ),
            'currency' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'USD'
            ),
            'start_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'end_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('active', 'inactive', 'suspended'),
                'default' => 'active'
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'image' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('partner_code');
        $this->dbforge->add_key('student_id');
        $this->dbforge->add_key('email');
        $this->dbforge->add_key('giving_frequency_id');
        $this->dbforge->add_key('giving_type_id');
        $this->dbforge->add_key('is_active');
        $this->dbforge->add_key('status');

        $this->dbforge->create_table('partners', TRUE, array('ENGINE' => 'InnoDB'));

        // Add foreign keys
        $this->db->query('ALTER TABLE `partners` ADD CONSTRAINT `partners_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `partners` ADD CONSTRAINT `partners_frequency_fk` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `partners` ADD CONSTRAINT `partners_type_fk` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('partners', TRUE);
    }
}