<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: EventModel
 * 
 * Automatically generated via CLI.
 */
class EventModel extends Model {
    protected $table = 'events';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}