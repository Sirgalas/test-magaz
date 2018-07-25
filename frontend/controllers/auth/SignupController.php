<?php

namespace frontend\controllers\auth;

use shop\services\auth\SignUpService;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use shop\forms\SignupForm;


/**
 * Site controller
 */
class SignupController extends Controller
{


    private $signUpService;



    public function __construct($id, $module, SignUpService $signUpService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->signUpService=$signUpService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */



    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post())) {
            try{
                $user = $this->signUpService->signup($form);
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }catch (\DomainException $e){
                Yii::$app->session->setFlash('error',$e->getMessage());
            }
            
        }
        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    public function actionConfirm($token){
        try{
            $this->signUpService->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed.');
            return $this->redirect(['login']);
        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goHome();
        }

    }



}
