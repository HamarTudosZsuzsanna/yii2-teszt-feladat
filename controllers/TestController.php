<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;

class TestController extends Controller
{
    public function actionInitRbac()
    {
        $auth = Yii::$app->authManager;

        // 1. Szerepkörök létrehozása
        $adminRole = $auth->createRole('admin');
        $studentRole = $auth->createRole('student');
        $auth->add($adminRole);
        $auth->add($studentRole);

        // 2. Jogosultság létrehozása
        $manageGroups = $auth->createPermission('ManageGroups');
        $manageGroups->description = 'Manage groups';
        $auth->add($manageGroups);

        // 3. Jogosultság hozzárendelése az admin szerepkörhöz
        $auth->addChild($adminRole, $manageGroups);

        // 4. Admin felhasználó létrehozása
        $adminForm = new SignupForm();
        $adminForm->username = 'admin';
        $adminForm->password = 'admin123';
        $adminForm->password_repeat = 'admin123';
        $adminUser = $adminForm->signup();

        if ($adminUser) {
            $auth->assign($adminRole, $adminUser->id);
        }

        // 5. Student felhasználó létrehozása
        $studentForm = new SignupForm();
        $studentForm->username = 'student';
        $studentForm->password = 'student123';
        $studentForm->password_repeat = 'student123';
        $studentUser = $studentForm->signup();

        if ($studentUser) {
            $auth->assign($studentRole, $studentUser->id);
        }

        return "RBAC rendszer inicializálva, felhasználók létrehozva.";
    }
}
