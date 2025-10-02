<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_auth
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('partner_model');
        $this->CI->load->library('session');
    }

    /**
     * Login partner
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return array
     */
    public function login($username, $password, $remember = false)
    {
        // Check if account is locked
        $partner = $this->CI->db->where('username', $username)
                                ->or_where('email', $username)
                                ->get('partners')
                                ->row_array();

        if (!$partner) {
            return ['status' => false, 'message' => 'Invalid username or password'];
        }

        // Check if account is locked
        if ($partner['locked_until'] && strtotime($partner['locked_until']) > time()) {
            $minutes = ceil((strtotime($partner['locked_until']) - time()) / 60);
            return ['status' => false, 'message' => "Account locked. Try again in {$minutes} minutes"];
        }

        // Check if account is active
        if ($partner['status'] != 'active') {
            return ['status' => false, 'message' => 'Your account is not active. Please contact support'];
        }

        // Verify password
        if (!password_verify($password, $partner['password'])) {
            // Increment login attempts
            $attempts = $partner['login_attempts'] + 1;
            $update = ['login_attempts' => $attempts];

            // Lock account after 5 failed attempts
            if ($attempts >= 5) {
                $update['locked_until'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            }

            $this->CI->db->where('id', $partner['id'])->update('partners', $update);

            return ['status' => false, 'message' => 'Invalid username or password'];
        }

        // Check if email is verified (optional)
        // if (!$partner['is_email_verified']) {
        //     return ['status' => false, 'message' => 'Please verify your email first'];
        // }

        // Reset login attempts
        $this->CI->db->where('id', $partner['id'])->update('partners', [
            'login_attempts' => 0,
            'locked_until' => NULL,
            'last_login' => date('Y-m-d H:i:s')
        ]);

        // Create session
        $session_data = [
            'partner_id' => $partner['id'],
            'partner_code' => $partner['partner_code'],
            'partner_name' => $partner['firstname'] . ' ' . $partner['lastname'],
            'partner_email' => $partner['email'],
            'partner_logged_in' => true
        ];

        $this->CI->session->set_userdata($session_data);

        // Log session
        $this->logSession($partner['id']);

        // Set remember me cookie
        if ($remember) {
            $this->setRememberCookie($partner['id']);
        }

        // Log activity
        $this->logActivity($partner['id'], 'login', 'Partner logged in');

        return ['status' => true, 'message' => 'Login successful', 'partner' => $partner];
    }

    /**
     * Register new partner
     * @param array $data
     * @return array
     */
    public function register($data)
    {
        // Check if email already exists
        $exists = $this->CI->db->where('email', $data['email'])->get('partners')->row();
        if ($exists) {
            return ['status' => false, 'message' => 'Email already registered'];
        }

        // Check if username already exists
        if (isset($data['username'])) {
            $exists = $this->CI->db->where('username', $data['username'])->get('partners')->row();
            if ($exists) {
                return ['status' => false, 'message' => 'Username already taken'];
            }
        }

        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Generate verification token
        $data['email_verification_token'] = bin2hex(random_bytes(32));
        $data['is_email_verified'] = 0;

        // Insert partner
        $partner_id = $this->CI->partner_model->add($data);

        if ($partner_id) {
            // Send verification email
            $this->sendVerificationEmail($data['email'], $data['email_verification_token']);

            return ['status' => true, 'message' => 'Registration successful. Please check your email to verify your account', 'partner_id' => $partner_id];
        }

        return ['status' => false, 'message' => 'Registration failed. Please try again'];
    }

    /**
     * Logout partner
     */
    public function logout()
    {
        $partner_id = $this->CI->session->userdata('partner_id');

        if ($partner_id) {
            // Log activity
            $this->logActivity($partner_id, 'logout', 'Partner logged out');

            // Delete session record
            $this->CI->db->where('session_id', session_id())->delete('partner_sessions');
        }

        // Destroy session
        $this->CI->session->unset_userdata('partner_id');
        $this->CI->session->unset_userdata('partner_code');
        $this->CI->session->unset_userdata('partner_name');
        $this->CI->session->unset_userdata('partner_email');
        $this->CI->session->unset_userdata('partner_logged_in');
        $this->CI->session->sess_destroy();

        // Delete remember me cookie
        setcookie('partner_remember', '', time() - 3600, '/');
    }

    /**
     * Check if partner is logged in
     * @return bool
     */
    public function is_logged_in()
    {
        return $this->CI->session->userdata('partner_logged_in') === true;
    }

    /**
     * Get logged in partner data
     * @return array|null
     */
    public function get_partner_data()
    {
        if (!$this->is_logged_in()) {
            return null;
        }

        $partner_id = $this->CI->session->userdata('partner_id');
        return $this->CI->partner_model->get($partner_id);
    }

    /**
     * Change password
     * @param int $partner_id
     * @param string $old_password
     * @param string $new_password
     * @return array
     */
    public function change_password($partner_id, $old_password, $new_password)
    {
        $partner = $this->CI->partner_model->get($partner_id);

        if (!password_verify($old_password, $partner['password'])) {
            return ['status' => false, 'message' => 'Current password is incorrect'];
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $updated = $this->CI->partner_model->update($partner_id, ['password' => $hashed]);

        if ($updated) {
            $this->logActivity($partner_id, 'password_changed', 'Password changed');
            return ['status' => true, 'message' => 'Password changed successfully'];
        }

        return ['status' => false, 'message' => 'Failed to change password'];
    }

    /**
     * Request password reset
     * @param string $email
     * @return array
     */
    public function request_password_reset($email)
    {
        $partner = $this->CI->db->where('email', $email)->get('partners')->row_array();

        if (!$partner) {
            return ['status' => false, 'message' => 'Email not found'];
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->CI->db->where('id', $partner['id'])->update('partners', [
            'password_reset_token' => $token,
            'password_reset_expires' => $expires
        ]);

        // Send reset email
        $this->sendPasswordResetEmail($email, $token);

        return ['status' => true, 'message' => 'Password reset link sent to your email'];
    }

    /**
     * Reset password with token
     * @param string $token
     * @param string $new_password
     * @return array
     */
    public function reset_password($token, $new_password)
    {
        $partner = $this->CI->db->where('password_reset_token', $token)
                                ->where('password_reset_expires >', date('Y-m-d H:i:s'))
                                ->get('partners')
                                ->row_array();

        if (!$partner) {
            return ['status' => false, 'message' => 'Invalid or expired reset token'];
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $this->CI->db->where('id', $partner['id'])->update('partners', [
            'password' => $hashed,
            'password_reset_token' => NULL,
            'password_reset_expires' => NULL
        ]);

        $this->logActivity($partner['id'], 'password_reset', 'Password reset via email');

        return ['status' => true, 'message' => 'Password reset successfully'];
    }

    /**
     * Verify email
     * @param string $token
     * @return array
     */
    public function verify_email($token)
    {
        $partner = $this->CI->db->where('email_verification_token', $token)->get('partners')->row_array();

        if (!$partner) {
            return ['status' => false, 'message' => 'Invalid verification token'];
        }

        $this->CI->db->where('id', $partner['id'])->update('partners', [
            'is_email_verified' => 1,
            'email_verification_token' => NULL
        ]);

        return ['status' => true, 'message' => 'Email verified successfully'];
    }

    /**
     * Log session
     */
    private function logSession($partner_id)
    {
        $this->CI->db->insert('partner_sessions', [
            'partner_id' => $partner_id,
            'session_id' => session_id(),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent()
        ]);
    }

    /**
     * Set remember me cookie
     */
    private function setRememberCookie($partner_id)
    {
        $token = bin2hex(random_bytes(32));
        setcookie('partner_remember', $token, time() + (86400 * 30), '/'); // 30 days
    }

    /**
     * Log activity
     */
    private function logActivity($partner_id, $type, $description)
    {
        $this->CI->db->insert('partner_activity_log', [
            'partner_id' => $partner_id,
            'activity_type' => $type,
            'description' => $description,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent()
        ]);
    }

    /**
     * Send verification email
     */
    private function sendVerificationEmail($email, $token)
    {
        $this->CI->load->library('email');

        $link = base_url('partnerportal/verify_email/' . $token);
        $message = "Please verify your email by clicking this link: <a href='{$link}'>{$link}</a>";

        $this->CI->email->from('noreply@rhemazimbabwe.com', 'Rhema Zimbabwe');
        $this->CI->email->to($email);
        $this->CI->email->subject('Verify Your Email - Rhema Zimbabwe Partners');
        $this->CI->email->message($message);
        $this->CI->email->send();
    }

    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($email, $token)
    {
        $this->CI->load->library('email');

        $link = base_url('partnerportal/reset_password/' . $token);
        $message = "Reset your password by clicking this link: <a href='{$link}'>{$link}</a>";

        $this->CI->email->from('noreply@rhemazimbabwe.com', 'Rhema Zimbabwe');
        $this->CI->email->to($email);
        $this->CI->email->subject('Reset Your Password - Rhema Zimbabwe Partners');
        $this->CI->email->message($message);
        $this->CI->email->send();
    }
}
