<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

class EventController extends OrgController {

    public function index() {
        // E. Access Control: Anyone can view the calendar
        $this->call->model('EventModel');
        $data['events'] = $this->EventModel->order_by('start_time', 'DESC');
        $this->call->view('event/calendar', $data);
    }

    public function create() {
        // E. Access Control: Only Executive Members and above can create/edit events
        $this->has_role(['Adviser', 'President', 'Treasurer', 'Secretary', 'Executive Member']); 

        if ($this->io->method() == 'post') {
            $this->call->model('EventModel');
            $event_data = [
                'title' => $this->io->post('title'),
                'description' => $this->io->post('description'),
                'start_time' => $this->io->post('start_time'),
                'end_time' => $this->io->post('end_time'),
                'location' => $this->io->post('location'),
                'created_by_user_id' => get_user_id()
            ];
            $this->EventModel->insert($event_data);
            set_flash_alert('success', 'Event scheduled successfully.');
            redirect(BASE_URL . '/org/events');
        }
        $this->call->view('event/create');
    }
}