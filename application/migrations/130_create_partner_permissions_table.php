<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partner_permissions_table extends CI_Migration
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
            'permission_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ),
            'permission_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE
            ),
            'is_granted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'granted_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'granted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'revoked_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'revoked_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'notes' => array(
                'type' => 'TEXT',
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
        $this->dbforge->add_key('permission_code');
        $this->dbforge->add_key('is_granted');

        $this->dbforge->create_table('partner_permissions', TRUE, array('ENGINE' => 'InnoDB'));

        // Add foreign key
        $this->db->query('ALTER TABLE `partner_permissions` ADD CONSTRAINT `partner_permissions_partner_fk` FOREIGN KEY (`partner_id`) REFERENCES `partners`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add unique constraint for partner_id and permission_code combination
        $this->db->query('ALTER TABLE `partner_permissions` ADD UNIQUE KEY `partner_permission_unique` (`partner_id`, `permission_code`)');
    }

    public function down()
    {
        $this->dbforge->drop_table('partner_permissions', TRUE);
    }
}