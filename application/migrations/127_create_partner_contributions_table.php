<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partner_contributions_table extends CI_Migration
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
            'contribution_date' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ),
            'currency' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'USD'
            ),
            'payment_method' => array(
                'type' => 'ENUM',
                'constraint' => array('cash', 'bank_transfer', 'mobile_money', 'credit_card', 'debit_card', 'cheque', 'online', 'other'),
                'default' => 'cash'
            ),
            'transaction_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'reference_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'receipt_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),
            'giving_type_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'giving_frequency_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('pending', 'completed', 'cancelled', 'failed', 'refunded'),
                'default' => 'completed'
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'attachment' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'recorded_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'approved_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'approved_at' => array(
                'type' => 'DATETIME',
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
        $this->dbforge->add_key('contribution_date');
        $this->dbforge->add_key('giving_type_id');
        $this->dbforge->add_key('giving_frequency_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('transaction_id');
        $this->dbforge->add_key('receipt_no');

        $this->dbforge->create_table('partner_contributions', TRUE, array('ENGINE' => 'InnoDB'));

        // Add foreign keys
        $this->db->query('ALTER TABLE `partner_contributions` ADD CONSTRAINT `contributions_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `partners`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `partner_contributions` ADD CONSTRAINT `contributions_type_fk` FOREIGN KEY (`giving_type_id`) REFERENCES `giving_types`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `partner_contributions` ADD CONSTRAINT `contributions_frequency_fk` FOREIGN KEY (`giving_frequency_id`) REFERENCES `giving_frequencies`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('partner_contributions', TRUE);
    }
}