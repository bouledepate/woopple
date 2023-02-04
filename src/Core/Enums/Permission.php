<?php

declare(strict_types=1);

namespace Core\Enums;

enum Permission: string
{
    case ACCESS_ADMIN_PANEL = 'oAccessAdminPanel';
    case ACCESS_USER_MANAGEMENT = 'oAccessUserManagement';
    case CREATE_USER = 'oCreateUser';
    case MODIFY_USER = 'oModifyUser';
    case BLOCK_USER = 'oBlockUser';
    case UNBLOCK_USER = 'oUnblockUser';
}