<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.07.18
 * Time: 22:07
 */

namespace common\bootstrap;

use frontend\services\auth\PasswordRessetFormSevice;
use frontend\services\auth\SignUpService;
use frontend\services\contact\ContactServices;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class,function ()use($app){
            return $app->mailer;
        });

        $container->setSingleton(ContactServices::class,[],[
            $app->params['adminEmail'],
        ]);

        $container->setSingleton(SignUpService::class,[],[
            $app->name
        ]);

    }

}