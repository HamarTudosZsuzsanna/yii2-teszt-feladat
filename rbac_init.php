<?php
// rbac_init.php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$auth = Yii::$app->authManager;

// Szerepkörök létrehozása
$admin = $auth->createRole('admin');
$student = $auth->createRole('student');

$auth->add($admin);
$auth->add($student);

// Jogosultság létrehozása
$manageGroups = $auth->createPermission('ManageGroups');
$manageGroups->description = 'Manage groups';
$auth->add($manageGroups);

// Jogosultság hozzárendelése az admin szerepkörhöz
$auth->addChild($admin, $manageGroups);

// Felhasználók hozzárendelése (példa user_id = 1 admin, user_id = 2 student)
if (\app\models\User::findOne(1)) {
    $auth->assign($admin, 1);
}
if (\app\models\User::findOne(2)) {
    $auth->assign($student, 2);
}

echo "RBAC inicializálva.\n";

