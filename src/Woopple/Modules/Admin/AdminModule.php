<?php

namespace Woopple\Modules\Admin;

class AdminModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        \Yii::$app->session->set('layoutKey', 'admin');
        \Yii::configure(
            object: $this,
            properties: require dirname(__DIR__, 4) . '/app/woopple/config/modules/admin.php'
        );
    }
}