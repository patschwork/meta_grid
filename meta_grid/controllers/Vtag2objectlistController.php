<?php

namespace app\controllers;

use Yii;
use app\models\VTag2ObjectListSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\Tag;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;


/**
 * Vtag2objectlistController implements the CRUD actions for VTag2ObjectList model.
 * 
 * THIS CONTROLLER IS NOT MANAGED BY META#GRID-GII
 */
class Vtag2objectlistController extends Controller
{	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRuleFilter::class,
                ],
                'rules' => [
					[
						'allow' => true,
						'actions' => ['index','view'],
						'roles' => ['@'],
					],
                ],
            ],			
        ];
    }
  
    /**
     * Lists all VTag2ObjectList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VTag2ObjectListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}