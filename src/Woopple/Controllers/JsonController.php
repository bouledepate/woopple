<?php

namespace Woopple\Controllers;

use Woopple\Components\Rbac\Rbac;
use Woopple\Models\Structure\Team;
use yii\helpers\ArrayHelper;
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

    public function actionTeams(): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id = $parents[0];
                $output = array_map(function (Team $team) {
                    return ['id' => $team->id, 'name' => $team->name];
                }, Team::findAll(['department_id' => $id]));

                return ['output' => $output, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}