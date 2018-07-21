<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.07.18
 * Time: 22:07
 */

namespace common\bootstrap;

use frontend\services\auth\PasswordRessetFormSevice;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(PasswordRessetFormSevice::class,[],[
            $app->params['supportEmail'] => $app->name . ' robot']
        );

    }

}