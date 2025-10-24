<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_giving_setting_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all giving settings for a partner
     * @param int $partner_id
     * @return array
     */
    public function getByPartnerId($partner_id)
    {
        $this->db->select('partner_giving_settings.*, giving_types.name as type_name, giving_types.code as type_code')
                 ->from('partner_giving_settings')
                 ->join('giving_types', 'giving_types.id = partner_giving_settings.giving_type_id', 'left')
                 ->where('partner_giving_settings.partner_id', $partner_id)
                 ->where('partner_giving_settings.is_active', 1)
                 ->order_by('giving_types.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get active giving settings for a partner
     * @param int $partner_id
     * @return array
     */
    public function getActiveSettings($partner_id)
    {
        return $this->getByPartnerId($partner_id);
    }

    /**
     * Get giving setting by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->db->select('partner_giving_settings.*, giving_types.name as type_name')
                 ->from('partner_giving_settings')
                 ->join('giving_types', 'giving_types.id = partner_giving_settings.giving_type_id', 'left')
                 ->where('partner_giving_settings.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Add new giving setting
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        if ($this->db->insert('partner_giving_settings', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update giving setting
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_giving_settings', $data);
    }

    /**
     * Delete giving setting
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_giving_settings');
    }

    /**
     * Delete all settings for a partner
     * @param int $partner_id
     * @return bool
     */
    public function deleteByPartnerId($partner_id)
    {
        $this->db->where('partner_id', $partner_id);
        return $this->db->delete('partner_giving_settings');
    }

    /**
     * Save multiple giving settings for a partner
     * This will replace all existing settings
     * @param int $partner_id
     * @param array $settings Array of settings with giving_type_id and amount
     * @return bool
     */
    public function saveSettings($partner_id, $settings)
    {
        // Start transaction
        $this->db->trans_start();

        // Delete existing settings
        $this->deleteByPartnerId($partner_id);

        // Insert new settings
        foreach ($settings as $setting) {
            if (!empty($setting['giving_type_id']) && !empty($setting['amount']) && $setting['amount'] > 0) {
                $data = [
                    'partner_id' => $partner_id,
                    'giving_type_id' => $setting['giving_type_id'],
                    'amount' => $setting['amount'],
                    'currency' => isset($setting['currency']) ? $setting['currency'] : 'USD',
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('partner_giving_settings', $data);
            }
        }

        // Complete transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Update or insert giving setting
     * @param int $partner_id
     * @param int $giving_type_id
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function upsert($partner_id, $giving_type_id, $amount, $currency = 'USD')
    {
        // Check if setting exists
        $existing = $this->db->where('partner_id', $partner_id)
                             ->where('giving_type_id', $giving_type_id)
                             ->get('partner_giving_settings')
                             ->row();

        if ($existing) {
            // Update existing
            return $this->update($existing->id, [
                'amount' => $amount,
                'currency' => $currency,
                'is_active' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insert new
            return $this->add([
                'partner_id' => $partner_id,
                'giving_type_id' => $giving_type_id,
                'amount' => $amount,
                'currency' => $currency,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Get total giving amount for a partner (sum of all active giving types)
     * @param int $partner_id
     * @return float
     */
    public function getTotalGivingAmount($partner_id)
    {
        $this->db->select_sum('amount');
        $this->db->where('partner_id', $partner_id);
        $this->db->where('is_active', 1);

        $query = $this->db->get('partner_giving_settings');
        $result = $query->row();

        return $result->amount ? $result->amount : 0;
    }

    /**
     * Check if partner has giving settings
     * @param int $partner_id
     * @return bool
     */
    public function hasSettings($partner_id)
    {
        $count = $this->db->where('partner_id', $partner_id)
                         ->where('is_active', 1)
                         ->count_all_results('partner_giving_settings');

        return $count > 0;
    }

    /**
     * Get giving types with amounts for a partner
     * Returns all giving types with amount 0 if not set
     * @param int $partner_id
     * @return array
     */
    public function getGivingTypesWithAmounts($partner_id)
    {
        // Get all giving types
        $this->db->select('giving_types.*, 
                          COALESCE(pgs.amount, 0) as amount,
                          COALESCE(pgs.currency, "USD") as currency,
                          COALESCE(pgs.is_active, 0) as is_active,
                          pgs.id as setting_id')
                 ->from('giving_types')
                 ->join('partner_giving_settings pgs', 
                        'pgs.giving_type_id = giving_types.id AND pgs.partner_id = ' . $partner_id, 
                        'left')
                 ->where('giving_types.is_active', 1)
                 ->order_by('giving_types.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
}






