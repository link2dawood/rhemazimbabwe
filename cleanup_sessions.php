<?php
// Session Cleanup Script
// Run this periodically to clean old sessions
require_once "index.php";
$CI =& get_instance();
$CI->load->database();

// Delete sessions older than 2 hours
$CI->db->where("timestamp <", time() - 7200);
$CI->db->delete("ci_sessions");

echo "Old sessions cleaned up successfully!";
?>