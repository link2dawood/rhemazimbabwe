<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_giving_frequencies extends CI_Migration
{
    public function up()
    {
        $data = array(
            array(
                'name' => 'Once-Off',
                'description' => 'One time contribution',
                'code' => 'once_off',
                'days_interval' => NULL,
                'is_active' => 1,
                'sort_order' => 1
            ),
            array(
                'name' => 'Weekly',
                'description' => 'Every week contribution',
                'code' => 'weekly',
                'days_interval' => 7,
                'is_active' => 1,
                'sort_order' => 2
            ),
            array(
                'name' => 'Monthly',
                'description' => 'Every month contribution',
                'code' => 'monthly',
                'days_interval' => 30,
                'is_active' => 1,
                'sort_order' => 3
            ),
            array(
                'name' => 'Quarterly',
                'description' => 'Every three months contribution',
                'code' => 'quarterly',
                'days_interval' => 90,
                'is_active' => 1,
                'sort_order' => 4
            ),
            array(
                'name' => 'Annually',
                'description' => 'Once a year contribution',
                'code' => 'annually',
                'days_interval' => 365,
                'is_active' => 1,
                'sort_order' => 5
            )
        );

        $this->db->insert_batch('giving_frequencies', $data);
    }

    public function down()
    {
        $this->db->where_in('code', array('once_off', 'weekly', 'monthly', 'quarterly', 'annually'));
        $this->db->delete('giving_frequencies');
    }
}