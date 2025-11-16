<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class OrgController extends Controller {

    // Load necessary helpers/libraries and enforce login before controller actions
    public function before_action() {
        $this->call->helper('common');
        $this->call->database();

        if (!logged_in()) {
            set_flash_alert('danger', 'You must be logged in to view this page.');
            redirect(BASE_URL . '/login');
            exit; 
        }

        // CRITICAL: Ensure role is in session, fetch if missing
        if (!isset($_SESSION['role'])) {
            $user_id = get_user_id();
            $user_data = $this->db->table('users')->select('role')->where('id', $user_id)->get();
            if ($user_data) {
                $_SESSION['role'] = $user_data['role'];
            } else {
                session_destroy();
                set_flash_alert('danger', 'Session invalid. Please log in again.');
                redirect(BASE_URL . '/login');
                exit;
            }
        }
    }
    
    /**
     * Helper to render content inside the main layout.
     */
    protected function _render($content_view, $data = [], $title = 'Maestro Dashboard') {
        $data['content_view'] = $content_view;
        $data['title'] = $title;
        // The layout file, 'layout/main', will take $data and render $content_view
        $this->call->view('layout/main', $data); 
    }

    /**
     * Centralized role check for Feature E
     */
    protected function has_role($allowed_roles) {
        $user_role = $_SESSION['role'] ?? 'General Member';
        $allowed_roles = is_array($allowed_roles) ? $allowed_roles : [$allowed_roles];
        
        if (!in_array($user_role, $allowed_roles)) {
            set_flash_alert('danger', 'Access Denied. You do not have the required role (' . implode(', ', $allowed_roles) . ').');
            redirect(BASE_URL . '/org/dashboard');
            exit;
        }
    }

    // This will be the main entry point after login
    public function dashboard() {
        $data = [
            'user_role' => $_SESSION['role']
        ];
        $this->_render('dashboard/index', $data, 'Welcome to Maestro'); 
    }
}