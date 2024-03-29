<?php

namespace Woopple\Components\Widgets;

use hail812\adminlte\widgets\Menu as AdminlteMenu;

class Menu extends AdminlteMenu
{
    public string $layout = 'site';

    public $options = [
        'class' => 'nav nav-pills nav-sidebar flex-column nav-legacy',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false'
    ];

    public function init(): void
    {
        $path = \Yii::getAlias('@wooppleApp') . "/config/navigation/sidebar/{$this->layout}.php";
        $this->items = require $path;
        $this->checkAccess();
    }

    private function checkAccess(): void
    {
        foreach ($this->items as $key => $value) {
            if (isset($value['access']) && !\Yii::$app->user->can($value['access'])) {
                unset($this->items[$key]);
            }
        }

        foreach ($this->items as $key => $value) {
            if (isset($value['items'])) {
                $available = array_map(
                    function (array $item) {
                        return isset($item['access']) && \Yii::$app->user->can($item['access']) ? $item : null;
                    }, $value['items']
                );
                $this->items[$key]['items'] = array_filter($available);
            }
        }
    }
}