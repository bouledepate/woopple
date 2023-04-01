<?php

return array_merge(
    require 'admin.php', [
        '/profile/update' => 'profile/update-profile',
        '/profile/<login>' => 'profile/profile',
        '/profile' => 'profile/profile'
    ]
);