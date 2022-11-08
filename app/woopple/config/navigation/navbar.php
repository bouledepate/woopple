<?php

/**
 * This file provides configuration for top navigation bar.
 * Default navigation link seems like:
 * ["title" => "Custom title", "url" => "http://example.com", "access" => Yii::$app->user->isGuest]
 * Access attribute must be declared as a boolean type or permission name. Default value is `true`.
 *
 * If link must contain dropdown list, you can do it by adding `items` attribute.
 * Attribute body contains similar link items.
 *
 * Dropdown lists can contain divider elements. Add it into your dropdown list by using `divider` => true` attribute.
 *
 * Example of navigation list:
 *
 * return [
 *      ['title' => 'Hello world', 'url' => '#', 'access' => true],
 *      ['title' => 'Hello world 2', 'url' => '#', 'items' => [
 *          ['title' => 'Hello world 3', 'url' => '#', 'access' => 'oViewAdminPanel'],
 *          ['title' => 'Hello world 4', 'url' => '#']
 *      ]],
 *      ['title' => 'Hello world 5', 'url' => '#', 'items' => [
 *          ['title' => 'Hello world 6', 'url' => '#'],
 *          ['title' => 'Hello world 7', 'url' => '#', 'items' => [
 *              ['title' => 'Hello world 8', 'url' => '#'],
 *              ['divider' => true],
 *              ['title' => 'Hello world 10', 'url' => '#'],
 *          ]]
 *      ]],
 * ];
 *
 */

return [
    ['title' => 'Hello world', 'url' => '#'],
    ['title' => 'Hello world 2', 'url' => '#', 'items' => [
        ['title' => 'Hello world 3', 'url' => '#'],
        ['title' => 'Hello world 4', 'url' => '#']
    ]],
    ['title' => 'Hello world 5', 'url' => '#', 'items' => [
        ['title' => 'Hello world 6', 'url' => '#'],
        ['title' => 'Hello world 7', 'url' => '#', 'items' => [
            ['title' => 'Hello world 8', 'url' => '#'],
            ['divider' => true],
            ['title' => 'Hello world 10', 'url' => '#'],
        ]]
    ]],
];