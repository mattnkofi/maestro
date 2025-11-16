<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

class MemberController extends OrgController {

    public function index() {
        // E. Multi-Level Access Control: Only Adviser and President can see the full list
        $this->has_role(['Adviser', 'President']); 

        $this->call->model('MemberModel');
        $data['members'] = $this->MemberModel->all();

        $this->call->view('member/index', $data);
    }

    public function edit($id) {
        // E. Multi-Level Access Control: Only Adviser can change roles
        $this->has_role('Adviser'); 

        $this->call->model('MemberModel');
        $this->call->library('form_validation');

        // A. Handle form submission (Update role/details)
        if ($this->io->method() == 'post' && $this->form_validation->submitted()) {
            $role = $this->io->post('role');
            $this->MemberModel->update($id, ['role' => $role]);
            set_flash_alert('success', 'Member updated successfully.');
            redirect(BASE_URL . '/org/members');
        }

        $data['member'] = $this->MemberModel->find($id);
        $this->call->view('member/edit', $data);
    }
}