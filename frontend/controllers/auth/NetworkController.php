<?php

namespace frontend\controllers\auth;

use shop\services\auth\NetworkService;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\authclient\AuthAction;

class NetworkController extends Controller
{
    public $service;

    public function __construct(string $id, $module, NetworkService $networkService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service=$networkService;
    }

    public function actions()
    {
        return [
            'auth'=>[
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ]
        ];
    }

    public function onAuthSuccess(ClientInterface $client):void
    {
        $network=$client->getId();
        $attributes=$client->getUserAttributes();
        $identity=ArrayHelper::getValue($attributes, 'id');

        try
        {
            $user=$this->service->auth($network, $identity);
            Yii::$app->user->login($user,Yii::$app->params['user.rememberMeDuration']);
        }catch (\DomainException $e)
        {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

}