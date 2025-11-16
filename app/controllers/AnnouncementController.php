<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

class AnnouncementController extends OrgController {

    public function index() {
        // D. Announcement Board: Display all public announcements
        $this->call->model('AnnouncementModel');
        $data['announcements'] = $this->AnnouncementModel->filter(['is_public' => 1])->order_by('created_at', 'DESC');
        $this->call->view('announcement/index', $data);
    }

    public function create() {
        // E. Access Control: Only Officers can post announcements
        $this->has_role(['Adviser', 'President', 'Treasurer', 'Secretary']); 

        if ($this->io->method() == 'post') {
            $this->call->model('AnnouncementModel');

            $announcement_data = [
                'subject' => $this->io->post('subject'),
                'content' => $this->io->post('content'),
                'is_public' => $this->io->post('is_public') ? 1 : 0,
                'created_by_user_id' => get_user_id()
            ];
            $this->AnnouncementModel->insert($announcement_data);

            // D. Send updates or memos (Email integration)
            $this->send_announcement_email($announcement_data);

            set_flash_alert('success', 'Announcement posted and emails sent.');
            redirect(BASE_URL . '/org/announcements');
        }
        $this->call->view('announcement/create');
    }

    private function send_announcement_email($announcement_data) {
        // Placeholder logic: retrieve all member emails and send
        $this->call->model('MemberModel');
        $members = $this->MemberModel->select('email')->all(); 

        $emails = array_column($members, 'email');

        $subject = 'NEW MAESTRO ANNOUNCEMENT: ' . $announcement_data['subject'];
        $body = '<h1>' . htmlspecialchars($announcement_data['subject']) . '</h1>'
                . '<p>' . nl2br(htmlspecialchars($announcement_data['content'])) . '</p>';

        foreach ($emails as $email) {
            // Leverage the Mailer library for professional sending
            $this->Mailer
                ->to($email)
                ->subject($subject)
                ->html($body)
                ->send();
        }
    }
}