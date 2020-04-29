<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Da\User\Filter\AccessRuleFilter;
use Symfony\Component\VarDumper\VarDumper;
use yii\filters\AccessControl;
use yii\base\ErrorException;

class ShorthelpController extends Controller
{
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
                        'roles' => ['admin'],
                    ],
					[
						'allow' => true,
						'actions' => ['index'],
						'roles' => ['@'],
					]
                ],
            ],			
        ];
    }

    public function actionIndex($helptextid)
    {
        // each helptext has each own file with an id
        try {
            return $this->render($helptextid);
        } 
        // if view file not found give an useable error to the user
        catch (yii\base\ViewNotFoundException $vnfe) 
        {
            return $this->render('nohelptextid', [
                'helptextid' => $helptextid,
            ]);
        }
        // if something else happens throw a regular exception
        catch (ErrorException $e) {
            throw $e;
        }
    }
}