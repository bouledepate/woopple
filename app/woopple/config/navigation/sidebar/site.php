<?php

/**
 * [
        'label' => 'Starter Pages',
        'icon' => 'tachometer-alt',
        'badge' => '<span class="right badge badge-info">2</span>',
        'items' => [
            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
        ]
    ],
    ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
    ['label' => 'Yii2 PROVIDED', 'header' => true],
    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
    ['label' => 'Level1'],
    [
        'label' => 'Level1',
        'items' => [
            ['label' => 'Level2', 'iconStyle' => 'far'],
            [
                'label' => 'Level2',
                'iconStyle' => 'far',
                'items' => [
                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                ]
            ],
            ['label' => 'Level2', 'iconStyle' => 'far']
        ]
    ],
    ['label' => 'Level1'],
    ['label' => 'LABELS', 'header' => true],
    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
 */

return [
    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
];