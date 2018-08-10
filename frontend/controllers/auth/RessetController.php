<?php

namespace frontend\controllers\auth;

use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use shop\forms\auth\PasswordResetRequestForm;
use shop\services\auth\PasswordRessetFormSevice;
use shop\forms\auth\ResetPasswordForm;

class RessetController extends Controller
{

    private $paswordRessetService;

    public function __construct($id, $module, PasswordRessetFormSevice $passwordRessetSevice,$config = [])
    {
        parent::__construct($id, $module, $config);
        $this->paswordRessetService=$passwordRessetSevice;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->paswordRessetService->request($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {

        try {
            $this->paswordRessetService->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $form= new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->paswordRessetService->reset($token,$form);
                Yii::$app->session->setFlash('success', 'New password saved.');
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->goHome();
        }

        return $this->render('confirm', [
            'model' => $form,
        ]);
    }





}
