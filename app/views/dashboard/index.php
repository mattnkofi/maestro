<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// $user_role is passed via the controller's $data array
$role = $user_role ?? 'General Member';
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <p class="text-xl text-gray-700 mb-4">You are currently logged in with the role of <strong><?= html_escape($role); ?></strong>.</p>
    
    <h2 class="text-2xl font-semibold mb-3">Your Permissions:</h2>
    <ul class="list-disc list-inside space-y-1 text-gray-600">
        <?php if ($role == 'Adviser'): ?>
            <li>Full administrative access to all features.</li>
        <?php elseif ($role == 'President'): ?>
            <li>Management access to members, events, and all core functions.</li>
        <?php elseif ($role == 'Treasurer'): ?>
            <li>Access to Financial Records (FR, AR) and event scheduling.</li>
        <?php elseif ($role == 'Secretary'): ?>
            <li>Access to Resolutions (RESO) and announcement posting.</li>
        <?php elseif ($role == 'Executive Member'): ?>
            <li>Access to event scheduling and general announcements.</li>
        <?php else: // General Member ?>
            <li>View-only access to the event calendar, announcements, and documents repository.</li>
        <?php endif; ?>
    </ul>
    
    <p class="mt-4 text-gray-500 italic">Use the sidebar to navigate to your accessible features.</p>
</div>