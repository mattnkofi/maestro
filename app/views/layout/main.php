<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Ensure the session role is loaded
$user_role = $_SESSION['role'] ?? 'General Member';
$user_name = $_SESSION['username'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maestro | <?= $title ?? 'Dashboard' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar { background-color: #004314ff; min-height: 100vh; color: white; }
        .sidebar a { display: block; padding: 1rem; border-radius: 0.5rem; transition: background-color 0.2s; }
        .sidebar a:hover { background-color: #005e19ff; }
    </style>
</head>
<body>
    <div class="flex">
        <aside class="sidebar w-64 p-4">
            <h1 class="text-2xl font-bold mb-6">MAESTRO</h1>
            <p class="text-sm italic mb-4 border-b border-gray-600 pb-2">
                Welcome, <?= html_escape($user_name); ?><br>
                Role: <strong><?= html_escape($user_role); ?></strong>
            </p>

            <nav class="space-y-2">
                <a href="<?= BASE_URL ?>/org/dashboard" class="bg-green-700/50 hover:bg-green-700">🏠 Dashboard</a>

                <?php if (in_array($user_role, ['Adviser', 'President'])): ?>
                    <a href="<?= BASE_URL ?>/org/members">👥 Member Management</a>
                <?php endif; ?>

                <?php if (in_array($user_role, ['Adviser', 'President', 'Treasurer', 'Secretary', 'Executive Member'])): ?>
                    <a href="<?= BASE_URL ?>/org/events/create">🗓️ Schedule Event</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/org/events">📅 Event Calendar</a>

                <?php if (in_array($user_role, ['Adviser', 'President', 'Treasurer', 'Secretary'])): ?>
                    <a href="<?= BASE_URL ?>/org/documents/upload">📤 Upload Document</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/org/documents">🗃️ Document Repository</a>

                <?php if (in_array($user_role, ['Adviser', 'President', 'Treasurer', 'Secretary'])): ?>
                    <a href="<?= BASE_URL ?>/org/announcements/create">📢 Post Announcement</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/org/announcements">📰 Announcement Board</a>

                <a href="<?= BASE_URL ?>/logout" class="text-red-300 hover:bg-red-900 mt-4">🚪 Logout</a>
            </nav>
        </aside>

        <main class="flex-1 p-8 bg-gray-50">
            <?php if (function_exists('flash_alert')) flash_alert(); ?> 
            
            <h1 class="text-3xl font-bold text-gray-800 mb-6"><?= $title ?? 'Section' ?></h1>
            <div class="content">
                <?php $this->call->view($content_view, $data); ?>
            </div>
        </main>
    </div>
</body>
</html>