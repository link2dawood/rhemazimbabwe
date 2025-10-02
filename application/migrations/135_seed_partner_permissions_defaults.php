<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This migration creates a reference table for available permissions
 * The actual partner_permissions table will be populated when partners are assigned permissions
 */
class Migration_Seed_partner_permissions_defaults extends CI_Migration
{
    public function up()
    {
        // Create a helper table to store available permission types
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
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
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'sort_order' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
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
        $this->dbforge->add_key('permission_code');
        $this->dbforge->create_table('partner_permission_types', TRUE, array('ENGINE' => 'InnoDB'));

        // Insert default permission types
        $data = array(
            array(
                'permission_name' => 'Library Access',
                'permission_code' => 'library',
                'description' => 'Access to library resources and books',
                'is_active' => 1,
                'sort_order' => 1
            ),
            array(
                'permission_name' => 'Online Courses',
                'permission_code' => 'online_courses',
                'description' => 'Access to online courses and learning materials',
                'is_active' => 1,
                'sort_order' => 2
            ),
            array(
                'permission_name' => 'Download Centre',
                'permission_code' => 'download_centre',
                'description' => 'Access to download centre and digital resources',
                'is_active' => 1,
                'sort_order' => 3
            ),
            array(
                'permission_name' => 'Google Meet',
                'permission_code' => 'gmeet',
                'description' => 'Access to Google Meet live classes',
                'is_active' => 1,
                'sort_order' => 4
            ),
            array(
                'permission_name' => 'Zoom',
                'permission_code' => 'zoom',
                'description' => 'Access to Zoom live classes',
                'is_active' => 1,
                'sort_order' => 5
            )
        );

        $this->db->insert_batch('partner_permission_types', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('partner_permission_types', TRUE);
    }
}