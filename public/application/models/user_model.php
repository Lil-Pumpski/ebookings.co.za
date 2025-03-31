<?php

class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function validate($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->where('verified', 1);
        $query = $this->db->get('user');

        if ($query && $query->num_rows() == 1)
        {
            return true;
        }
        return false;
    }

    function get_user_role($user_id, $company_id) {
        $role = null;
        $this->db->where('user_id', $user_id);
        $this->db->where('company_id', $company_id);
        $this->db->where_in('permission', array('is_employee', 'is_manager', 'is_owner', 'is_admin', 'is_housekeeping'));

        $query = $this->db->get('user_permissions');

        if ($query && $query->num_rows() >= 1)
        {
            $results = $query->result_array();
            $role = $results[0]['permission'];
        }

        $sql = "SELECT 'is_admin' as permission FROM user_permissions as up
                LEFT JOIN company as c on c.company_id = up.company_id
                LEFT JOIN whitelabel_partner as wp on wp.id = c.partner_id
                LEFT JOIN whitelabel_partner_x_admin as wpxa on wp.id = wpxa.partner_id
                WHERE up.permission = 'is_owner' AND up.company_id = '$company_id'
                AND (wpxa.admin_id = '$user_id' OR '" . SUPER_ADMIN_USER_ID . "' = '$user_id')
                GROUP BY up.company_id";

        $query = $this->db->query($sql);
        if ($query && $query->num_rows() >= 1)
        {
            $results = $query->result_array();
            $role = $results[0]['permission'];
        }

        return $role;
    }

    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query && $query->num_rows() > 0) {
            return $query->row();
        }

        log_message('error', '[User_model] get_user_by_email(): DB error - ' . $this->db->last_query());
        return null;
    }

    public function get_user_profile($user_id)
    {
        $this->db->select('first_name, last_name, language, language_id');
        $this->db->from('users'); // or 'users' if you're storing this info there
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query && $query->num_rows() > 0) {
            return $query->row_array();
        }

        return [
            'first_name' => '',
            'last_name' => '',
            'language' => 'english',
            'language_id' => 1
        ];
    }

    public function get_latest_company_id($user_id)
    {
        $this->db->select('company_id');
        $this->db->from('user_permissions');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query && $query->num_rows() > 0) {
            return $query->row(); // 👈 returns as an object with ->company_id
        }

        return null;
    }

    function get_user_by_id($id)
    {
        $this->db->select('id, email, password, banned, ban_reason, created, activated');
        $this->db->where('id', $id);
        $query = $this->db->get('users');

        if ($query && $query->num_rows() > 0) {
            return $query->row();
        }

        log_message('error', '[User_model] get_user_by_id() failed. Query: ' . $this->db->last_query());
        return null;
    }

    // (Apply the same $query check fix to other similar functions if needed...)
}

/* End of file user_model.php */
