<?php

namespace console\controllers;

use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;

        // Töröljük a korábbi RBAC adatokat, ha vannak
        $auth->removeAll();

        // Jogosultság létrehozása
        $manageGroups = $auth->createPermission('ManageGroups');
        $manageGroups->description = 'Manage groups';
        $auth->add($manageGroups);

        // Szerepkörök létrehozása
        $student = $auth->createRole('student');
        $auth->add($student);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        // Jogosultság hozzárendelése az admin szerepkörhöz
        $auth->addChild($admin, $manageGroups);

        // Teszt felhasználók hozzárendelése szerepkörhöz
        // Tegyük fel, hogy user id 1 az admin, user id 2 a student
        $auth->assign($admin, 1);
        $auth->assign($student, 2);

        echo "RBAC inicializálva.\n";
    }
}
