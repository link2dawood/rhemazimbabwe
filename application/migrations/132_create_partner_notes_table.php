<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partner_notes_table extends CI_Migration
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
            'partner_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'note_type' => array(
                'type' => 'ENUM',
                'constraint' => array('general', 'prayer_request', 'feedback', 'complaint', 'appreciation', 'follow_up', 'other'),
                'default' => 'general'
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'note' => array(
                'type' => 'TEXT',
                'null' => FALSE
            ),
            'priority' => array(
                'type' => 'ENUM',
                'constraint' => array('low', 'normal', 'high', 'urgent'),
                'default' => 'normal'
            ),
            'is_private' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Private notes visible only to staff'
            ),
            'is_pinned' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Pinned notes appear at the top'
            ),
            'attachment' => array(
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
            'updated_by' => array(
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
        $this->dbforge->add_key('partner_id');
        $this->dbforge->add_key('note_type');
        $this->dbforge->add_key('priority');
        $this->dbforge->add_key('is_pinned');

        $this->dbforge->create_table('partner_notes', TRUE, array('ENGINE' => 'InnoDB'));

        // Add foreign key
        $this->db->query('ALTER TABLE `partner_notes` ADD CONSTRAINT `partner_notes_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `partners`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('partner_notes', TRUE);
    }
}