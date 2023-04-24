<?php

return array_merge(
    require 'admin.php', require 'json.php', [
        '/profile/update' => 'profile/update-profile',
        '/profile/<login>' => 'profile/profile',
        '/profile' => 'profile/profile',
        '/hr/beginners' => 'hr/beginners',
        '/hr/employers' => 'hr/employers',
        '/hr/teams' => 'hr/teams',
        '/hr/teams/create' => 'hr/create-team',
        '/hr/teams/modify/<id>' => 'hr/modify-team',
    ]
);