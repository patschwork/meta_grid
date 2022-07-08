<?php

namespace app\controllers;

use Yii;
use app\models\Tag;
use yii\filters\VerbFilter;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

use app\models\MapObject2Tag;
use Symfony\Component\VarDumper\VarDumper;
use yii\helpers\Html;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends \app\controllers\base\TagController
{
	// Overwritten from \app\controllers\base\TagController
	/**
	 * Dummy function. Not used.
	 */
	private function getUserList()
	{
		$userList = array();
		return $userList;
	}
	
	// Overwritten from \vendor\meta_grid\controllers\MetaGridController
	/**
	 * Rules may depends on indivual records. Therefore most actions are allowed to users which are logged in.
	 * If a user may create/edit/delete a tag will be checked in the corresponding functions.
	 */
    public function behaviors()
    {
		if (YII_ENV_DEV)
		{
			$this->registerControllerRole();
		}
		
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
						'actions' => ['index','view'],
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'actions' => ['create', 'update'],
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'roles' => ['@'],
					],
					[
						'allow' => true,
						'actions' => ['saveglobal'],
						'roles' => ['@'],
					],
                ],
            ],			
        ];
    }

	// Overwritten from \app\controllers\base\TagController
	/**
	 * Tag management is different to other object_types. This will be checked if a user may create the tag. 
	 * Personal tags may always be created by the user.
	 */
    public function actionCreate()
    {
		$initMode = "personal";		
		$model = new Tag();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
			$tagMode = Yii::$app->request->post()["TagMode"];
			if ($tagMode=="personal")
			{
				$model->fk_user_id = Yii::$app->user->id;
			}
			
			 if (!empty($model->fkProject->id))
				 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) 
				 {
					 throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
					 return;
				}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				        	return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'userList' => $this->getUserList(),		// autogeneriert ueber gii/CRUD
'projectListDisables' => $this->getdisabledProjectList(),
'mode' => $initMode,
]);
}
}

	// Overwritten from \app\controllers\base\TagController
	/**
	 * Tag management is different to other object_types. This will be checked if a user may edit the tag. 
	 * Personal tags may always be edited by the user.
	 */
	public function actionUpdate($id)
	{
		
		$model = $this->findModel($id);
		
		$mode = "personal";
		if ($model->fk_project_id !== null)
		{
			$mode = "project";
		}
		if ($model->fk_user_id !== null)
		{
			$mode = "personal";
		}

		if ($model->fk_project_id === null && $model->fk_user_id === null)
		{
			$mode = "global";
		}

		if (!empty($model->fkProject->id))
			if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit))
			{
				throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
				return;
			}    
			
			
			if ($model->load(Yii::$app->request->post()))
			{
				$tagMode = Yii::$app->request->post()["TagMode"];
				if ($tagMode=="personal")
				{
					$model->fk_user_id = Yii::$app->user->id;
					$model->fk_project_id = null;
				}
				if ($tagMode=="global")
				{
					$model->fk_user_id = null;
					$model->fk_project_id = null;
				}

				if ($model->save()) {
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
			else {
				return $this->render('update', [
					'model' => $model,
					'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
	'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
	'userList' => $this->getUserList(),		// autogeneriert ueber gii/CRUD
	'projectListDisables' => $this->getdisabledProjectList(),
	'mode' => $mode,
	]);
	}
	}

	// Overwritten from \vendor\meta_grid\controllers\MetaGridController
	/**
	 * User is able to delete personal tags. Therefore there is a non standard check.
	 */
    public function actionDelete($id)
    {


		if (!empty($this->findModel($id)->fkProject->id))
		{
			if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) 
			{
				throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
				return;	
			}
		}

		try {
			$model = $this->findModel($id);
			if ($model->fk_user_id !== null)
			{
				if ($model->fk_user_id !== Yii::$app->user->id)
				{
					{throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
						return;	}    	
				}
			}
			$model->delete();
			return $this->redirect(['index']);
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return $this->redirect(Url::previous());  // Url::remember() is set in index-view
		}
	}
	
	// Custom functions for Tag

	/**
	 * User may see projects but may not edit/delete.
	 * For this case the project item will be shown but is not selectable in the dropdown component.
	 */
	private function getdisabledProjectList()
	{
		$disProjectList = array();
		$disProjectList = $this->getProjectList();
		foreach($disProjectList as $key => $project)
		{
			if ($key == null) continue;
			if (!in_array($key, Yii::$app->User->identity->permProjectsCanEdit))
			{
				$disProjectList[$key] = ['disabled' => true];
			}
			else
			{
				$disProjectList[$key] = [];
			}
		}
		return $disProjectList;
	}

	private function appendTags($id, $object_type_id, $values) 
	{
		$new_Tag_Ids = array();
		foreach($values as $value)
		{
			// Check if tags object exists
			if ($this->existsModelTag($value))
			{
				$tag_id = intval($value);
			}
			else 
			{
				// create a new user tag (only those - user managed tags - are allowed "on the fly")
				$modelTag = new Tag();
				$modelTag->name = $value;
				$modelTag->fk_user_id = \Yii::$app->getUser()->id;
				$modelTag->save();
				$tag_id = $modelTag->id;
				$new_Tag_Ids[$value] = $tag_id;
			}
			// Check if combination already exists
			if (!$this->existsModelMapObject2Tag($id, $object_type_id, $tag_id))
			{
				// if not, then create
				$modelMapObject2Tag = new MapObject2Tag();
				$modelMapObject2Tag->ref_fk_object_id = $id;
				$modelMapObject2Tag->ref_fk_object_type_id = $object_type_id;
				$modelMapObject2Tag->fk_tag_id = $tag_id;
				$modelMapObject2Tag->save();
				$modelMapObject2Tag = null;
			}
			$modelTag = null;
		}

		return $new_Tag_Ids;
	}

	private function removeTags($id, $object_type_id, $values, $new_Tag_Ids, $values_is_empty)
	{
		// Yii::warning('**********************0', var_export($values_is_empty,true));
		
		// Check if tags shall be removed
		$modelMapObject2Tag = new MapObject2Tag();
		$result = $modelMapObject2Tag
			->find()
			->where(['ref_fk_object_id' => $id, 'ref_fk_object_type_id' => $object_type_id])
			->all();
		foreach($result as $row)  // all existing tags
		{
			// if personal tags...
			// Yii::warning('**********************0', var_export(empty($row->fkTag->fk_user_id),true));
			if (!empty($row->fkTag->fk_user_id))
			{
				if($row->fkTag->fk_user_id <> -1 && $row->fkTag->fk_user_id !== null)
				{
					// do not remove personal tags if different user... 
					if ($row->fkTag->fk_user_id !== \Yii::$app->getUser()->id)
					{
						continue;
					}
					// if current user tags, it will be handeled
				}
			}
			if ($values_is_empty)
			{
				$row->delete();
				continue;
			}
			$found = false;
			foreach($values as $value)  // submitted tags
			{
				if ($this->existsModelTag($value))
				{
					if ($row['fk_tag_id'] == $value)
					{
						$found = true;
					}
				}
				// check for new tags
				if (array_key_exists($value, $new_Tag_Ids))
				{
					$found = true;
				}
			}
			if ($values === "") $found = false;
			if (!$found)
			{
				$row->delete();
			}
		}
	}

	/**
	* This function is called from an ajax call by the vendor\meta_grid\tag_select\TagSelectWidget.
	* The widget is used in all object_types, which may use tags (mainly all with a fk_project_id).
	* Adds and removes tags to the object_type. If a new tag is typed which is unknown yet, it will be created as a personal tag and added.
	*/
	public function actionSaveglobal()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$data = null;
		$result = null;
		if (Yii::$app->request->isAjax) {
			$data = Yii::$app->request->post();
			$values_raw = $data['values'];
			$id_raw = $data['id']; // this is the model id where it comes from aka object_id
			$object_type_id_raw = $data['object_type_id']; // this is the object type id where it comes from
			$id = intval($id_raw);
			$object_type_id = intval($object_type_id_raw);
			$values = explode(",", $values_raw);
			$values_is_empty=false;
			if ($values_raw === "") $values_is_empty=true;
			$new_Tag_Ids = $this->appendTags($id, $object_type_id, $values);
			$this->removeTags($id, $object_type_id, $values, $new_Tag_Ids, $values_is_empty);
		}
		return [
			'code' => 200,
		];
    }

	/**
	* This function will only be executed by the function $this->appendTags
	* $id is an int, if it already war created (Selectize holds the id as key)
	* $id will be a string value if unknown to the Selectize component.
	*   In this case Tag::find will not find anything and this is the signal to create a new personal tag in the database
	*/
	protected function existsModelTag($id)
	{
		$model=Tag::find()->where(['id' => $id])->one();

		if (!empty($model->id))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	protected function existsModelMapObject2Tag($ref_fk_object_id, $ref_fk_object_type_id, $fk_tag_id)
	{
		if (MapObject2Tag::find()->where(['ref_fk_object_id' => $ref_fk_object_id, 'ref_fk_object_type_id' => $ref_fk_object_type_id, 'fk_tag_id' => $fk_tag_id])->one() !== null)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* Return an array as VarDump to show all rights to user to use tags.
	* Only admins will see the link to this action in the NavMenu
	*/
	public function actionAdmininfomatrix()
	{
		$userRes = \vendor\meta_grid\user_model\User::find()->all();
		foreach($userRes as $user)
		{
			echo Html::beginTag("h1");
			echo "User Id: $user->id | Username: $user->username";
			echo Html::endTag("h1");
			$RBACHelper = new \vendor\meta_grid\helper\RBACHelper();
			// VarDumper::dump($RBACHelper->matrixRoleTag($user->id));
			print("<pre>".print_r($RBACHelper->matrixRoleTag($user->id),true)."</pre>");
		}
	
		echo Html::a("Back", Yii::$app->homeUrl);
		return;
	}
}