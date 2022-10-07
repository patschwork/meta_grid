<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Cookie;
use yii\helpers\Url;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'contact'],
                'rules' => [
                    [
                        'actions' => ['logout', 'contact'],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            $model->email = \Yii::$app->user->identity->email;
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    
    public function actionLanguage($language)
    {
    	Yii::$app->language = $language;
    	
    	$languageCookie = new Cookie([
    			'name' => 'language',
    			'value' => $language,
    			'expire' => time() + 60 * 60 * 24 * 30, // 30 days
    	]);
    	Yii::$app->response->cookies->add($languageCookie);
		$this->setFlashForPerspectiveFilters();

    	// return $this->goHome();
        return $this->redirect(Url::previous());
    }
    
	
	private function setFlashForPerspectiveFilters()
	{
		$session = Yii::$app->session;
		// Reset all flash messages
		for ($i=0;$i<=50;$i++)
		{
			$session->setFlash('perspective_filter_for_' . $i , null);
		}
		
		$model = new \app\models\PerspectiveFilter();
				
		// Read the attribut names
		$distinct_fk_object_type_ids = $model::find()->select(['ref_fk_object_type_id'])->distinct()->where(['fk_language_id' => Yii::$app->language])->all();
		$arr = array();
		foreach($distinct_fk_object_type_ids as $distinct_fk_object_type_id)
		{
			$session->setFlash('perspective_filter_for_' . $distinct_fk_object_type_id->ref_fk_object_type_id , Yii::t('app','The current perspective ({perspective}) has set a filter', ['perspective' => Yii::$app->language]));
		}
	}
}
