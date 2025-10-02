<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partner_reminders_table extends CI_Migration
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
            'reminder_type' => array(
                'type' => 'ENUM',
                'constraint' => array('contribution_due', 'missing_contribution', 'thank_you', 'custom', 'birthday', 'anniversary', 'renewal'),
                'default' => 'contribution_due'
            ),
            'reminder_date' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
            'reminder_time' => array(
                'type' => 'TIME',
                'null' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'send_via' => array(
                'type' => 'ENUM',
                'constraint' => array('email', 'sms', 'both', 'notification'),
                'default' => 'email'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('pending', 'sent', 'failed', 'cancelled'),
                'default' => 'pending'
            ),
            'sent_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'is_recurring' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'recurrence_pattern' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'daily, weekly, monthly, yearly'
            ),
            'next_reminder_date' => array(
                'type' => 'DATE',
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
        $this->dbforge->add_key('partner_id');
        $this->dbforge->add_key('reminder_date');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('reminder_type');

        $this->dbforge->create_table('partner_reminders', TRUE, array('ENGINE' => 'InnoDB'));

        // Add foreign key
        $this->db->query('ALTER TABLE `partner_reminders` ADD CONSTRAINT `partner_reminders_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `partners`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('partner_reminders', TRUE);
    }
}