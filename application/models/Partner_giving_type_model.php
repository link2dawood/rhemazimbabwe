<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_giving_type_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all giving types for a partner
     * @param int $partner_id
     * @return array
     */
    public function getByPartnerId($partner_id)
    {
        $this->db->select('partner_giving_types.*, giving_types.name as type_name, giving_types.description')
                 ->from('partner_giving_types')
                 ->join('giving_types', 'giving_types.id = partner_giving_types.giving_type_id', 'left')
                 ->where('partner_giving_types.partner_id', $partner_id)
                 ->where('partner_giving_types.is_active', 1)
                 ->order_by('giving_types.name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get total contribution amount for a partner
     * @param int $partner_id
     * @return float
     */
    public function getTotalAmount($partner_id)
    {
        $this->db->select_sum('amount');
        $this->db->where('partner_id', $partner_id);
        $this->db->where('is_active', 1);

        $query = $this->db->get('partner_giving_types');
        $result = $query->row();

        return $result->amount ? $result->amount : 0;
    }

    /**
     * Save partner giving types (replaces existing)
     * @param int $partner_id
     * @param array $giving_types Array of ['giving_type_id' => amount]
     * @param string $currency
     * @return bool
     */
    public function savePartnerGivingTypes($partner_id, $giving_types, $currency = 'USD')
    {
        // Start transaction
        $this->db->trans_start();

        // Deactivate all existing giving types for this partner
        $this->db->where('partner_id', $partner_id);
        $this->db->update('partner_giving_types', array('is_active' => 0));

        // Insert or update each giving type
        if (!empty($giving_types) && is_array($giving_types)) {
            foreach ($giving_types as $type_id => $amount) {
                if ($amount > 0 && $type_id > 0) {
                    // Check if record exists
                    $existing = $this->db->where('partner_id', $partner_id)
                                        ->where('giving_type_id', $type_id)
                                        ->get('partner_giving_types')
                                        ->row();

                    if ($existing) {
                        // Update existing record
                        $this->db->where('id', $existing->id);
                        $this->db->update('partner_giving_types', array(
                            'amount' => $amount,
                            'currency' => $currency,
                            'is_active' => 1,
                            'updated_at' => date('Y-m-d H:i:s')
                        ));
                    } else {
                        // Insert new record
                        $this->db->insert('partner_giving_types', array(
                            'partner_id' => $partner_id,
                            'giving_type_id' => $type_id,
                            'amount' => $amount,
                            'currency' => $currency,
                            'is_active' => 1,
                            'created_at' => date('Y-m-d H:i:s')
                        ));
                    }
                }
            }
        }

        // Calculate total amount
        $total_amount = $this->getTotalAmount($partner_id);

        // Update partners table with total
        $this->db->where('id', $partner_id);
        $this->db->update('partners', array('total_contribution_amount' => $total_amount));

        // Complete transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Delete giving type for partner
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_giving_types');
    }

    /**
     * Deactivate giving type for partner
     * @param int $id
     * @return bool
     */
    public function deactivate($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_giving_types', array('is_active' => 0));
    }

    /**
     * Get giving types breakdown for display
     * @param int $partner_id
     * @return string HTML formatted list
     */
    public function getGivingTypesDisplay($partner_id)
    {
        $types = $this->getByPartnerId($partner_id);

        if (empty($types)) {
            return '<span class="text-muted">No giving types set</span>';
        }

        $html = '<ul class="list-unstyled" style="margin:0;">';
        foreach ($types as $type) {
            $html .= '<li><i class="fa fa-check-circle text-success"></i> ';
            $html .= $type['type_name'] . ': ';
            $html .= '<strong>' . $type['currency'] . ' ' . number_format($type['amount'], 2) . '</strong>';
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get statistics for admin dashboard
     * @return array
     */
    public function getStatistics()
    {
        // Total by giving type
        $sql = "SELECT
                    gt.name as type_name,
                    gt.id as type_id,
                    COUNT(DISTINCT pgt.partner_id) as partner_count,
                    SUM(pgt.amount) as total_amount,
                    AVG(pgt.amount) as avg_amount
                FROM partner_giving_types pgt
                JOIN giving_types gt ON gt.id = pgt.giving_type_id
                WHERE pgt.is_active = 1
                GROUP BY gt.id
                ORDER BY total_amount DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
