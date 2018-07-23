<?php
namespace frontend\controllers;

use shop\services\auth\SignUpService;
use Yii;

use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use shop\forms\LoginForm;
use shop\forms\PasswordResetRequestForm;
use shop\services\auth\PasswordRessetFormSevice;
use shop\forms\ResetPasswordForm;
use shop\forms\SignupForm;
use shop\forms\ContactForm;
use shop\services\auth\AuthService;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private $paswordRessetService;

    private $contactFormService;

    private $signUpService;

    private $authService;

    public function __construct(
        string $id,
        $module,
        AuthService $authService,
        PasswordRessetFormSevice $passwordRessetSevice,
        ContactForm $contactForm,
        SignUpService $signUpService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->paswordRessetService=$passwordRessetSevice;
        $this->contactFormService=$contactForm;
        $this->signUpService=$signUpService;
        $this->authService=$authService;
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->login()){
            try{
                $user=$this->authService->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
                $this->goBack();
            }catch(\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
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

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
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

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
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

        return $this->render('requestPasswordResetToken', [
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
    public function actionResetPassword($token)
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

        return $this->render('resetPassword', [
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
