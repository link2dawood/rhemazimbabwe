<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_giving_types extends CI_Migration
{
    public function up()
    {
        $data = array(
            array(
                'name' => 'Type A',
                'description' => 'Type A Partnership - Basic giving level',
                'code' => 'type_a',
                'is_active' => 1,
                'sort_order' => 1
            ),
            array(
                'name' => 'Type B',
                'description' => 'Type B Partnership - Intermediate giving level',
                'code' => 'type_b',
                'is_active' => 1,
                'sort_order' => 2
            ),
            array(
                'name' => 'Type C',
                'description' => 'Type C Partnership - Premium giving level',
                'code' => 'type_c',
                'is_active' => 1,
                'sort_order' => 3
            )
        );

        $this->db->insert_batch('giving_types', $data);
    }

    public function down()
    {
        $this->db->where_in('code', array('type_a', 'type_b', 'type_c'));
        $this->db->delete('giving_types');
    }
}