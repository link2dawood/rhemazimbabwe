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
    public function getAllPermissionTypes($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }

        $this->db->order_by('permission_name', 'ASC');
        $query = $this->db->get('partner_permission_types');

        return $query->result();
    }

    /**
     * Get permission type by ID
     * @param int $id
     * @return object|null
     */
    public function getPermissionTypeById($id)
    {
        return $this->db->where('id', $id)->get('partner_permission_types')->row();
    }

    /**
     * Get permission type by code
     * @param string $code
     * @return object|null
     */
    public function getPermissionTypeByCode($code)
    {
        return $this->db->where('permission_code', $code)->get('partner_permission_types')->row();
    }

    /**
     * Add new permission type
     * @param array $data
     * @return int|bool
     */
    public function addPermissionType($data)
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
    public function updatePermissionType($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_permission_types', $data);
    }

    /**
     * Delete permission type
     * @param int $id
     * @return bool
     */
    public function deletePermissionType($id)
    {
        // Check if permission type is being used by any partner
        $count = $this->db->where('permission_code', $this->getPermissionTypeById($id)->permission_code)
                          ->count_all_results('partner_permissions');

        if ($count > 0) {
            return false; // Cannot delete if in use
        }

        $this->db->where('id', $id);
        return $this->db->delete('partner_permission_types');
    }

    /**
     * Toggle permission type active status
     * @param int $id
     * @return bool
     */
    public function togglePermissionTypeStatus($id)
    {
        $permission_type = $this->getPermissionTypeById($id);

        if (!$permission_type) {
            return false;
        }

        $new_status = $permission_type->is_active ? 0 : 1;

        return $this->updatePermissionType($id, array('is_active' => $new_status));
    }

    /**
     * Get permissions for a specific partner
     * @param int $partner_id
     * @return array
     */
    public function getByPartnerId($partner_id)
    {
        $this->db->select('partner_permissions.*, partner_permission_types.permission_name, partner_permission_types.description')
                 ->from('partner_permissions')
                 ->join('partner_permission_types', 'partner_permission_types.permission_code = partner_permissions.permission_code', 'left')
                 ->where('partner_permissions.partner_id', $partner_id);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Grant permission to partner
     * @param int $partner_id
     * @param string $permission_code
     * @param int $granted_by
     * @param string $expires_at
     * @return bool
     */
    public function grantPermission($partner_id, $permission_code, $granted_by = null, $expires_at = null)
    {
        $data = array(
            'partner_id' => $partner_id,
            'permission_code' => $permission_code,
            'is_granted' => 1,
            'granted_by' => $granted_by,
            'granted_at' => date('Y-m-d H:i:s'),
            'expires_at' => $expires_at
        );

        // Check if permission already exists
        $existing = $this->db->where('partner_id', $partner_id)
                             ->where('permission_code', $permission_code)
                             ->get('partner_permissions')
                             ->row();

        if ($existing) {
            // Update existing
            $this->db->where('partner_id', $partner_id);
            $this->db->where('permission_code', $permission_code);
            return $this->db->update('partner_permissions', $data);
        } else {
            // Insert new
            return $this->db->insert('partner_permissions', $data);
        }
    }

    /**
     * Revoke permission from partner
     * @param int $partner_id
     * @param string $permission_code
     * @return bool
     */
    public function revokePermission($partner_id, $permission_code)
    {
        $this->db->where('partner_id', $partner_id);
        $this->db->where('permission_code', $permission_code);
        return $this->db->update('partner_permissions', array('is_granted' => 0));
    }

    /**
     * Check if partner has specific permission
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
     * Get permission usage count
     * @param string $permission_code
     * @return int
     */
    public function getPermissionUsageCount($permission_code)
    {
        return $this->db->where('permission_code', $permission_code)
                        ->where('is_granted', 1)
                        ->count_all_results('partner_permissions');
    }

    /**
     * Get permissions as dropdown options
     * @return array
     */
    public function getPermissionDropdown()
    {
        $permissions = $this->getAllPermissionTypes(true);
        $dropdown = array();

        foreach ($permissions as $permission) {
            $dropdown[$permission->permission_code] = $permission->permission_name;
        }

        return $dropdown;
    }
}