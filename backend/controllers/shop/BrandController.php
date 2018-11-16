<?php

namespace backend\controllers\shop;

use backend\forms\shop\BrandSearch;
use shop\entities\shop\Brand;
use shop\forms\manage\shop\BrandForm;
use shop\services\manage\shop\BrandManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BrandController extends Controller
{
    private $service;

    public function __construct(string $id, $module, BrandManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service=$service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel= new BrandSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionViews($id)
    {
        return $this->render('view',
            [
                'brand' => $this->findModel($id),
            ]);
    }

    public function actionCreate()
    {
        $form=new BrandForm();
        if($form->load(Yii::$app->request->post())&&$form->validate()){
            try{
                $brand=$this->service->create($form);
                $this->redirect(['view','id'=>$brand->id]);
            }catch (\RuntimeException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
    }


    public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        $form=new BrandForm($model);
        if($form->load(Yii::$app->request->post())&&$form->validate()){
            try{
                $this->service->edit($id,$form);
                $this->redirect(['view','id'=>$id]);
            }catch (\RuntimeException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
    }

    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->redirect('index');
    }




    /**
     * @param integer $id
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Brand
    {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
