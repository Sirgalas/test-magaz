<?php


namespace frontend\controllers;

use shop\services\contact\ContactServices;
use Yii;
use yii\web\Controller;
use shop\forms\ContactForm;

class ContactController extends Controller
{
    public $contactFormService;

    public function __construct($id, $module, ContactServices $contactFormService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->contactFormService=$contactFormService;
    }
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->contactFormService->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $form,
        ]);

    }

}