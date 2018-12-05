<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.11.18
 * Time: 8:35
 */

namespace controllers\shop;

use backend\forms\shop\CategorySearch;
use shop\forms\manage\shop\CategoryForm;
use forms\manage\shop\product\CategoriesForm;
use shop\entities\shop\Categories;
use shop\services\manage\shop\CategoryManageService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class CategoryController extends Controller
{
    public $service;

    public function __construct(string $id, $module,CategoryManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service=$service;
    }

    public function  behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'move-up' => ['POST'],
                    'move-down' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
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
            'category' => $this->findModel($id),
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $form=new CategoryForm();
        if($form->load(Yii::$app->request->post())&&$form->validate()){
            try{
                $category=$this->service->create($form);
                return $this->redirect(['views','id'=>$category->id]);
            }catch (\RuntimeException $e){
                Yii::error($e);
                Yii::$app->session->setFlash($e->getMessage());
            }
        }
        return $this->redirect('create',[
            'model' => $form
        ]);
    }

    public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        $form=new CategoryForm($model);
        if($form->load(Yii::$app->request->post())&&$form->validate()){
            try{
                $this->service->edit($model->id,$form);
                return $this->redirect(['views','id'=>$id]);
            }catch (\RuntimeException $e){
                Yii::error($e);
                Yii::$app->session->setFlash($e->getMessage());
            }
        }
        return $this->redirect('update',[
            'model'=>$form,
            'category'=>$model
        ]);
    }

    public function actionMoveUp($id)
    {
        $this->service->moveUp($id);
        return $this->redirect($id);
    }

    public function actionMoveDown($id)
    {
        $this->service->moveDown($id);
        return $this->redirect($id);
    }
    /**
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Categories
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
