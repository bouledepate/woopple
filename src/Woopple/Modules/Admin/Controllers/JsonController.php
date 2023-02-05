<?php

namespace Woopple\Modules\Admin\Controllers;

use Woopple\Components\Rbac\Rbac;
use yii\web\Controller;
use yii\web\Response;

class JsonController extends Controller
{
    public function actionRoleInfo(string $key): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (\Yii::$app->request->isAjax) {
            $role = Rbac::role($key);
            return [
                'key' => $key,
                'description' => $role->description,
                'permissions' => $role->permissions
            ];
        }

        return [];
    }
}