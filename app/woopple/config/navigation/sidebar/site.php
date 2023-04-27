<?php

/**
 * [
 * 'label' => 'Starter Pages',
 * 'icon' => 'tachometer-alt',
 * 'badge' => '<span class="right badge badge-info">2</span>',
 * 'items' => [
 * ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
 * ['label' => 'Inactive Page', 'iconStyle' => 'far'],
 * ]
 * ],
 * ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
 * ['label' => 'Yii2 PROVIDED', 'header' => true],
 * ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
 * ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
 * ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
 * ['label' => 'Level1'],
 * [
 * 'label' => 'Level1',
 * 'items' => [
 * ['label' => 'Level2', 'iconStyle' => 'far'],
 * [
 * 'label' => 'Level2',
 * 'iconStyle' => 'far',
 * 'items' => [
 * ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
 * ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
 * ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
 * ]
 * ],
 * ['label' => 'Level2', 'iconStyle' => 'far']
 * ]
 * ],
 * ['label' => 'Level1'],
 * ['label' => 'LABELS', 'header' => true],
 * ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
 * ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
 * ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
 */

use Core\Enums\Permission;
use Woopple\Models\User\User;

return [
    [
        'label' => Yii::t('navigation', 'user-tests'),
        'url' => ['/tests'],
        'iconStyle' => 'fas',
        'icon' => 'file-alt',
        'badge' => "<span class=\"right badge badge-danger\">"
            . Yii::$app->user->identity?->getTestsCount()
            . "</span>",
    ],
    [
        'label' => Yii::t('navigation', 'lead-section'),
        'access' => Permission::ACCESS_LEAD_SECTION->value,
        'header' => true
    ],
    [
        'label' => Yii::t('navigation', 'lead-tests'),
        'url' => ['test/control'],
        'iconStyle' => 'fas',
        'icon' => 'file-alt',
        'access' => Permission::TESTS_CONTROL->value
    ],
    [
        'label' => Yii::t('navigation', 'human-resource'),
        'access' => Permission::HR_ACCESS->value,
        'header' => true
    ],
    [
        'label' => Yii::t('navigation', 'hr-personal'),
        'icon' => 'id-card',
        'access' => Permission::HR_ACCESS_PERSONAL->value,
        'items' => [
//            [
//                'label' => Yii::t('navigation', 'hr-employers'),
//                'url' => ['hr/employers'],
//                'iconStyle' => 'fas',
//                'icon' => 'users',
//                'access' => Permission::HR_ACCESS_EMPLOYERS->value
//            ],
            [
                'label' => Yii::t('navigation', 'hr-new-users'),
                'url' => ['hr/beginners'],
                'iconStyle' => 'fas',
                'icon' => 'user-graduate',
                'access' => Permission::HR_ACCESS_BEGINNERS->value,
                'badge' => "<span class=\"right badge badge-danger\">"
                    . User::find()->where(['status' => \Woopple\Components\Enums\AccountStatus::CREATED->value])->count()
                    . "</span>",
            ]
        ]
    ],
    [
        'label' => Yii::t('navigation', 'hr-structure'),
        'icon' => 'database',
        'access' => Permission::HR_ACCESS_STRUCTURE->value,
        'items' => [
            [
                'label' => Yii::t('navigation', 'hr-departments'),
                'url' => ['hr/departments'],
                'iconStyle' => 'fas',
                'icon' => 'network-wired',
                'access' => Permission::VIEW_DEPARTMENT_LIST->value
            ],
            [
                'label' => Yii::t('navigation', 'hr-teams'),
                'url' => ['hr/teams'],
                'iconStyle' => 'fas',
                'icon' => 'frog',
                'access' => Permission::VIEW_TEAM_LIST->value
            ]
        ]
    ]
];