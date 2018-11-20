<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.11.18
 * Time: 8:10
 */

namespace controllers\shop;


use backend\forms\Shop\TagSearch;
use shop\entities\shop\Tags;
use shop\forms\manage\Shop\TagForm;
use shop\services\manage\shop\TagManageService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class TagController extends Controller
{
    private $service;

    public function __construct(string $id, $module, TagManageService $service, array $config = [])
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
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'tag' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form=new TagForm();
        if($form->validate(Yii::$app->request->post())&&$form->validate()){
            try{
                $tags=$this->service->create($form);
                return $this->redirect(['view','id'=>$tags->id]);
            }catch (\RuntimeException $e){
                Yii::error($e);
                Yii::$app->session->setFlash($e->getMessage());
            }
        }
        return $this->render('create',[
            'model'=>$form
        ]);
    }

    public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        $form= new TagForm($model);
        if($form->validate(Yii::$app->request->post())&&$form->validate()){
            try{
                $this->service->edit($model->id,$form);
                return $this->redirect(['view','id'=>$model->id]);
            }catch (\RuntimeException $e){
                Yii::error($e);
                Yii::$app->session->setFlash($e->getMessage());
            }
        }
        return $this->render('create',[
            'model'=>$form,
            'tag'=>$model
        ]);

    }
    /**
     * @param integer $id
     * @return Tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Tags
    {
        if (($model = Tags::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
