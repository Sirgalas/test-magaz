<?php

namespace frontend\controllers\cabinet;

use shop\services\auth\NetworkService;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\helpers\Url;
use Yii;

class NetworkController extends Controller
{

    public $service;

    public function __construct(string $id, $module, NetworkService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service=$service;
    }

    public function actions()
    {
        return [
            'attach'=>[
                'class'=>AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index']),
            ]
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        $network=$client->getId();
        $attributes=$client->getUserAttributes();
        $identity=ArrayHelper::getValue($attributes, 'id');

        try{
            $this->service->attach(Yii::$app->user->id, $network, $identity);
            Yii::$app->session->setFlash('success', 'Network is successfully attached.');
        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

}