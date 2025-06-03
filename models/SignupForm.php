<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User; // Az adatbázisba mentő modell

class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat'], 'required'],
            ['username', 'string', 'min' => 3],
            ['password', 'string', 'min' => 4],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A jelszavak nem egyeznek.'],
        ];
    }

    public function signup()
{
    $user = new User();
    $user->username = $this->username;
    $user->password = Yii::$app->security->generatePasswordHash($this->password);
    $user->auth_key = Yii::$app->security->generateRandomString();

    return $user->save() ? $user : null;
}
}
