<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Permission_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all permission types
     * @param bool $active_only
     * @return array
     */
    public function getAllTypes($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }

        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('partner_permission_types');

        return $query->result();
    }

    /**
     * Get partner permissions
     * @param int $partner_id
     * @return array
     */
    public function getByPartnerId($partner_id)
    {
        $this->db->select('partner_permissions.*,
                          partner_permission_types.description,
                          partner_permission_types.sort_order')
                 ->from('partner_permissions')
                 ->join('partner_permission_types', 'partner_permission_types.permission_code = partner_permissions.permission_code', 'left')
                 ->where('partner_permissions.partner_id', $partner_id)
                 ->order_by('partner_permission_types.sort_order', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get permission by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        return $this->db->where('id', $id)->get('partner_permissions')->row();
    }

    /**
     * Check if partner has permission
     * @param int $partner_id
     * @param string $permission_code
     * @return bool
     */
    public function hasPermission($partner_id, $permission_code)
    {
        $count = $this->db->where('partner_id', $partner_id)
                         ->where('permission_code', $permission_code)
                         ->where('is_granted', 1)
                         ->count_all_results('partner_permissions');

        return $count > 0;
    }

    /**
     * Grant permission to partner
     * @param int $partner_id
     * @param string $permission_code
     * @param int $granted_by
     * @return int|bool
     */
    public function grant($partner_id, $permission_code, $granted_by)
    {
        // Get permission details
        $permission_type = $this->db->where('permission_code', $permission_code)
                                   ->get('partner_permission_types')
                                   ->row();

        if (!$permission_type) {
            return false;
        }

        // Check if permission already exists
        $existing = $this->db->where('partner_id', $partner_id)
                            ->where('permission_code', $permission_code)
                            ->get('partner_permissions')
                            ->row();

        $data = array(
            'partner_id' => $partner_id,
            'permission_name' => $permission_type->permission_name,
            'permission_code' => $permission_code,
            'is_granted' => 1,
            'granted_by' => $granted_by,
            'granted_at' => date('Y-m-d H:i:s')
        );

        if ($existing) {
            // Update existing permission
            $this->db->where('id', $existing->id);
            if ($this->db->update('partner_permissions', $data)) {
                return $existing->id;
            }
            return false;
        } else {
            // Insert new permission
            if ($this->db->insert('partner_permissions', $data)) {
                return $this->db->insert_id();
            }
            return false;
        }
    }

    /**
     * Revoke permission from partner
     * @param int $partner_id
     * @param string $permission_code
     * @param int $revoked_by
     * @return bool
     */
    public function revoke($partner_id, $permission_code, $revoked_by)
    {
        $data = array(
            'is_granted' => 0,
            'revoked_by' => $revoked_by,
            'revoked_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('partner_id', $partner_id);
        $this->db->where('permission_code', $permission_code);

        return $this->db->update('partner_permissions', $data);
    }

    /**
     * Grant multiple permissions to partner
     * @param int $partner_id
     * @param array $permission_codes
     * @param int $granted_by
     * @return bool
     */
    public function grantMultiple($partner_id, $permission_codes, $granted_by)
    {
        $this->db->trans_start();

        foreach ($permission_codes as $code) {
            $this->grant($partner_id, $code, $granted_by);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Sync partner permissions (grant specified, revoke others)
     * @param int $partner_id
     * @param array $permission_codes
     * @param int $user_id
     * @return bool
     */
    public function sync($partner_id, $permission_codes, $user_id)
    {
        $this->db->trans_start();

        // Get all permission types
        $all_permissions = $this->getAllTypes(true);

        foreach ($all_permissions as $permission_type) {
            if (in_array($permission_type->permission_code, $permission_codes)) {
                // Grant permission
                $this->grant($partner_id, $permission_type->permission_code, $user_id);
            } else {
                // Revoke permission
                $this->revoke($partner_id, $permission_type->permission_code, $user_id);
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Get granted permissions for partner
     * @param int $partner_id
     * @return array
     */
    public function getGrantedPermissions($partner_id)
    {
        $this->db->select('partner_permissions.permission_code,
                          partner_permissions.permission_name')
                 ->from('partner_permissions')
                 ->where('partner_id', $partner_id)
                 ->where('is_granted', 1);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Delete all permissions for partner
     * @param int $partner_id
     * @return bool
     */
    public function deleteByPartnerId($partner_id)
    {
        $this->db->where('partner_id', $partner_id);
        return $this->db->delete('partner_permissions');
    }

    /**
     * Get partners by permission
     * @param string $permission_code
     * @param bool $granted_only
     * @return array
     */
    public function getPartnersByPermission($permission_code, $granted_only = true)
    {
        $this->db->select('partners.*,
                          partner_permissions.granted_at,
                          partner_permissions.granted_by')
                 ->from('partner_permissions')
                 ->join('partners', 'partners.id = partner_permissions.partner_id')
                 ->where('partner_permissions.permission_code', $permission_code);

        if ($granted_only) {
            $this->db->where('partner_permissions.is_granted', 1);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get permission statistics
     * @return array
     */
    public function getStatistics()
    {
        $this->db->select('partner_permissions.permission_code,
                          partner_permissions.permission_name,
                          COUNT(*) as total_grants,
                          SUM(CASE WHEN partner_permissions.is_granted = 1 THEN 1 ELSE 0 END) as active_grants')
                 ->from('partner_permissions')
                 ->group_by('partner_permissions.permission_code');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Add permission type (admin function)
     * @param array $data
     * @return int|bool
     */
    public function addType($data)
    {
        if ($this->db->insert('partner_permission_types', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update permission type
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateType($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_permission_types', $data);
    }
}