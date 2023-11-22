<?php
// models/RegistrationForm.php

namespace app\models;

use yii\base\Model;

class RegistrationForm extends Model
{
    public $username;
    public $password;
    public $email;

    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
        ];
    }
}