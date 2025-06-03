<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Group;
use yii\web\Response;

class GroupController extends Controller
{
    public function actionHierarchy()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $onlyRoots = \Yii::$app->request->get('onlyRoots', false);
        $hierarchy = \app\models\Group::getHierarchy($onlyRoots);
        return $hierarchy;
    }
}
