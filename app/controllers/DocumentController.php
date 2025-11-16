<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

class DocumentController extends OrgController {

    protected $upload_dir = 'uploads/documents/';

    public function __construct() {
        parent::__construct();
        $this->call->helper('directory');
        $this->call->helper('download'); // For downloading
        $this->call->library('upload'); // For uploading
    }

    public function index() {
        // C. Document Repository: Access depends on the type (FR, AR, RESO)
        $this->call->model('DocumentModel');
        $data['documents'] = $this->DocumentModel->order_by('uploaded_at', 'DESC');
        $this->call->view('document/index', $data);
    }

    public function upload() {
        // E. Access Control: Only Treasurer (FR/AR) and Secretary (RESO) can upload
        $this->has_role(['Adviser', 'President', 'Treasurer', 'Secretary']);

        if ($this->io->method() == 'post' && isset($_FILES['document_file'])) {
            $this->call->model('DocumentModel');

            // 1. Configure and run upload
            $this->upload->set_dir($this->upload_dir);
            $this->upload->encrypt_name(); // Security feature
            $this->upload->allowed_extensions(['pdf', 'doc', 'docx', 'xlsx']);

            if ($this->upload->do_upload(FALSE)) {
                $file_data = [
                    'title' => $this->io->post('title'),
                    'file_name' => $this->upload->get_filename(), // Encrypted name
                    'file_path' => $this->upload_dir . $this->upload->get_filename(),
                    'document_type' => $this->io->post('document_type'),
                    'uploaded_by_user_id' => get_user_id()
                ];

                // 2. Save file metadata to database
                $this->DocumentModel->insert($file_data);
                set_flash_alert('success', 'Document uploaded successfully. Document Type: ' . $file_data['document_type']);
                redirect(BASE_URL . '/org/documents');
            } else {
                $errors = implode('<br>', $this->upload->get_errors());
                set_flash_alert('danger', 'Upload Failed: ' . $errors);
                redirect(BASE_URL . '/org/documents');
            }
        }
        $this->call->view('document/upload');
    }

    public function download($id) {
        $this->call->model('DocumentModel');
        $doc = $this->DocumentModel->find($id);

        if ($doc) {
            // E. Basic Document Access Control (Example: Only officers can download FR/AR)
            $user_role = $this->db->table('users')->select('role')->where('id', get_user_id())->get()['role'];
            if (in_array($doc['document_type'], ['FR', 'AR']) && !in_array($user_role, ['Adviser', 'President', 'Treasurer', 'Secretary', 'Executive Member'])) {
                set_flash_alert('danger', 'Access Denied for this document type.');
                redirect(BASE_URL . '/org/documents');
            }

            // C. Force download the file
            force_download($doc['file_path'], $doc['title']);
        } else {
            show_404('404 Not Found', 'Document not found.');
        }
    }
}