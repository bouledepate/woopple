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
        '/lead/tests/' => 'test/control',
        '/lead/tests/new' => 'test/create-test',
        '/tests' => 'test/user-tests',
        '/tests/start/<id>' => 'test/start-test',
        '/tests/result/<id>' => 'test/user-results',
        '/tests/result/<id>/by/<login>' => 'test/user-results-by-login'
    ]
);