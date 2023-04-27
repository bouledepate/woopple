<?php

declare(strict_types=1);

namespace Core\Enums;

enum Permission: string
{
    case ACCESS_ADMIN_PANEL = 'oAccessAdminPanel';
    case ACCESS_USER_MANAGEMENT = 'oAccessUserManagement';
    case ACCESS_SECURITY_CONTROL = 'oAccessSecurityControl';
    case ACCESS_RESTORE_PASS_CONTROL = 'oAccessRestorePasswordControl';
    case ACCESS_BLACKLIST_CONTROL = 'oAccessBlacklistControl';
    case RESTORE_PASSWORD = 'oRestorePassword';
    case CREATE_USER = 'oCreateUser';
    case MODIFY_USER = 'oModifyUser';
    case BLOCK_USER = 'oBlockUser';
    case UNBLOCK_USER = 'oUnblockUser';
    case ACCESS_DEPARTMENT_CONTROL = 'oAccessDepartmentControl';
    case CREATE_DEPARTMENT = 'oCreateDepartment';
    case MODIFY_DEPARTMENT = 'oModifyDepartment';
    case REMOVE_DEPARTMENT = 'oDeleteDepartment';
    case HR_ACCESS = 'oAccessHumanResourceSection';
    case HR_ACCESS_EMPLOYERS = 'oAccessHREmployersSection';
    case HR_ACCESS_BEGINNERS = 'oAccessHRBeginnersSection';
    case FILL_PROFILE = 'oFillProfile';
    case HR_ACCESS_PERSONAL = 'oViewPersonalSection';
    case HR_ACCESS_STRUCTURE = 'oViewStructureSection';
    case VIEW_DEPARTMENT_LIST = 'oViewDepartmentList';
    case VIEW_TEAM_LIST = 'oViewTeamList';
    case ACCESS_LEAD_SECTION = 'oAccessLeadSection';
    case TESTS_CONTROL = 'oTestsControl';
}