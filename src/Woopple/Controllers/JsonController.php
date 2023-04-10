<?php

namespace Woopple\Controllers;

use Woopple\Components\Rbac\Rbac;
use Woopple\Models\Structure\Team;
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

    public function actionTeams(int $department): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (\Yii::$app->request->isAjax) {
            return Team::findAll(['department_id' => $department]);
        }

        return [];
    }
}