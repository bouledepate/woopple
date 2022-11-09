<?php

namespace Woopple\Components\Widgets;

class Navbar extends Widget
{
    public bool $notifications = false;
    public bool $messages = false;
    public bool $fullscreen = false;
    public bool $defaultAccessRule = true;

    protected array $items = [];
    protected string $content = '';

    private const DIVIDER = '<li class="dropdown-divider"></li>';

    private const LINK_TYPE_NORMAL = 'navbar_link';
    private const LINK_TYPE_DROPDOWN = 'navbar_dropdown_link';

    private const DROPDOWN_TYPE_NORMAL = 'normal';
    private const DROPDOWN_TYPE_SUBMENU = 'submenu';

    public function run()
    {
        return $this->render('navbar', [
            'navbarItems' => $this->content,
            'showMessages' => $this->messages,
            'messagesCount' => 0,
            'messages' => '',
            'showNotifications' => $this->notifications,
            'notificationsCount' => 0,
            'notifications' => '',
            'fullscreen' => $this->fullscreen
        ]);
    }

    public function init(): void
    {
        $this->uploadNavigationRules();
        $this->content = $this->renderItems();
    }

    protected function renderItems(array $items = [], bool $child = false): string
    {
        $content = '';
        $items = empty($items) ? $this->items : $items;
        foreach ($items as $index => $item) {
            $access = isset($item['access'])
                ? $this->checkAccess($item['access'])
                : $this->defaultAccessRule;
            if (isset($item['items'])) {
                $dropdownType = $child ? self::DROPDOWN_TYPE_SUBMENU : self::DROPDOWN_TYPE_NORMAL;
                $dropdownItems = $this->renderItems($item['items'], true);
                $content .= $this->renderDropdown(
                    type: $dropdownType,
                    id: $index,
                    title: $dropdownItems,
                    items: $item['title'],
                    access: $access
                );
            } elseif (isset($item['divider'])) {
                $content .= self::DIVIDER;
            } else {
                $linkType = $child ? self::LINK_TYPE_DROPDOWN : self::LINK_TYPE_NORMAL;
                $content .= $this->renderLink(
                    type: $linkType,
                    url: $item['url'],
                    title: $item['title'],
                    access: $access
                );
            }
        }
        return $content;
    }

    protected function renderLink(string $type, string $url, string $title, bool $access = true): string
    {
        return $this->render($type, [
            'url' => $url,
            'title' => $title,
            'access' => $access
        ]);
    }

    protected function renderDropdown(string $type, int $id, string $title, string $items, bool $access = true): string
    {
        $submenu = $type === self::DROPDOWN_TYPE_SUBMENU;
        return $this->render('navbar_dropdown', [
            'id' => 'dropdown-' . $id,
            'title' => $title,
            'items' => $items,
            'access' => $access,
            'submenu' => $submenu
        ]);
    }

    protected function uploadNavigationRules(): void
    {
        $path = \Yii::getAlias('@wooppleApp') . '/navigation/navbar.php';
        $this->items = require $path;
    }

    // todo: Реализовать после настройки RBAC
    protected function checkAccess(bool|string $permission): bool
    {
        return true;
    }
}