<?php

namespace app\controllers;

use Yii;
use app\models\VAllObjectsUnion;
use app\models\GlobalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Client;
use app\models\MapObject2Object;
use app\models\Project;
use Da\User\Filter\AccessRuleFilter;
use Symfony\Component\VarDumper\VarDumper;
use yii\filters\AccessControl;

/**
 * MapperV2Controller implements the CRUD actions for VAllObjectsUnion model.
 */
class MapperV2Controller extends Controller
{
	
	
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
        								'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
        						],
        				],
        		],
        ];
    }

    private function createRole($newRoleOrPermName, $authType, $description, $ruleName, $childRole, $childPerm)
    {
    	$auth = Yii::$app->authManager;
    	$checkRole = $auth->getRole($newRoleOrPermName);
    	$checkPerm = $auth->getPermission($newRoleOrPermName);
    	if ((is_null($checkRole) && $authType==="Role") || (is_null($checkPerm) && $authType==="Perm"))
    	{
    		if ($authType==="Role")
    		{
    			$newAuthObj = $auth->createRole($newRoleOrPermName);
    		}
    		else
    		{
    			if ($authType==="Perm")
    			{
    				$newAuthObj = $auth->createPermission($newRoleOrPermName);
    			}
    			else
    			{
    				throw "No supported authType";
    			}
    		}
    		$newAuthObj->ruleName = $ruleName;
    		if (!is_null($description))
    		{
    			$newAuthObj->description = $description;
    		}
    		 
    		$auth->add($newAuthObj);
    
    		if (!is_null($childRole))
    		{
    			$auth->addChild($auth->getRole($childRole), $newAuthObj);
    		}
    
    		if (!is_null($childPerm))
    		{
    			$auth->addChild($auth->getRole($childPerm), $newAuthObj);
    		}
    		return $newAuthObj;
    	}
    	return null;
    }
    
    private function registerControllerRole()
    {
    
    	$this->createRole("global-view", "Role", "May view all objectstypes", "isNotAGuest", null, null);
    	$newAuthorRole = $this->createRole("author", "Role", "May edit all objecttypes", "isNotAGuest", null, null);
    	if (!is_null($newAuthorRole))
    	{
    		Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-view"));
    	}
    
    	$newRoleName = 'view' ."-" . Yii::$app->controller->id;
    	$this->createRole($newRoleName, "Perm", "May only view objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-view", null);
    }
    
    
    /**
     * Lists all VAllObjectsUnion models.
     * @return mixed
     */
    public function actionIndex($from_object_id, $from_object_type_id, $from_url="")
    {
        $from_model = $this->findModel($id=$from_object_id, $fk_object_type_id=$from_object_type_id);
		
		$searchModel = new GlobalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$already_mapped_model = $this->alreadyMapped($from_object_id, $from_object_type_id);
		$already_mapped_listkey = $this->convertAlreadyMappedToListkey($already_mapped_model, $from_object_id, $from_object_type_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'from_model' => $from_model,
			'already_mapped_model' => $already_mapped_model,
			'already_mapped_listkey' => $already_mapped_listkey,
			'from_url' => $from_url,
        ]);
    }


	public function alreadyMapped($from_object_id, $from_object_type_id)
	{
		$already_mapped_model = MapObject2Object::find()->where(["ref_fk_object_id_1"=>$from_object_id, "ref_fk_object_type_id_1"=>$from_object_type_id])->orWhere(["ref_fk_object_id_2"=>$from_object_id, "ref_fk_object_type_id_2"=>$from_object_type_id])->asArray()->all();
		return $already_mapped_model;
	}

	private function convertAlreadyMappedToListkey($already_mapped_model, $from_object_id, $from_object_type_id)
	{
		$listkeys = [];
		foreach ($already_mapped_model as $mapping)
		{
			$a = $mapping["ref_fk_object_id_1"].";".$mapping["ref_fk_object_type_id_1"];
			$b = $mapping["ref_fk_object_id_2"].";".$mapping["ref_fk_object_type_id_2"];
			$listkeys[$a]=$a == $from_object_id.";".$from_object_type_id ? "Source" : "Already mapped";
			$listkeys[$b]=$b == $from_object_id.";".$from_object_type_id ? "Source" : "Already mapped";
		}
		return $listkeys;
	}

    /**
     * Finds the VAllObjectsUnion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $fk_object_type_id
     * @return VAllObjectsUnion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $fk_object_type_id)
    {
		$dependency = new \yii\caching\DbDependency();
		$dependency->sql="SELECT max(log_datetime) FROM v_LastChangesLog_List";
        if (($model = VAllObjectsUnion::find()->where(['id' => $id, 'fk_object_type_id' => $fk_object_type_id])->cache(NULL, $dependency)->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    // {... mapperv2
	// this works so far... 
	public function actionProcessselected()
	{
		$from_id = Yii::$app->request->post("from_id");
		$from_fk_object_type_id = Yii::$app->request->post("from_fk_object_type_id");

		$select = Yii::$app->request->post('selection'); //checkbox (array)
		

		// https://stackoverflow.com/questions/29272150/insert-multiple-data-into-database-in-yii-2
		$bulkInsertArray = array();

		foreach($select as $to_map_listkey)
		{
			// VarDumper::dump($to_map_listkey); 

			$ref_fk_object_id_2 = explode(";", $to_map_listkey)[0];
			$ref_fk_object_type_id_2 = explode(";", $to_map_listkey)[1];

			$already_mapped_model = $this->alreadyMapped($from_id, $from_fk_object_type_id);
			$already_mapped_listkey = $this->convertAlreadyMappedToListkey($already_mapped_model, $from_id, $from_fk_object_type_id);

			// if ($ref_fk_object_id_2 !== $from_id && $ref_fk_object_type_id_2 !== $from_fk_object_type_id) // do not map self
			if (! array_key_exists($to_map_listkey, $already_mapped_listkey))
			{
				$bulkInsertArray[]=[
					'ref_fk_object_id_1'=>$from_id,
					'ref_fk_object_type_id_1'=>$from_fk_object_type_id,
					'ref_fk_object_id_2'=>$ref_fk_object_id_2,
					'ref_fk_object_type_id_2'=>$ref_fk_object_type_id_2,
					// 'fk_mapping_qualifier_id'=>NULL
					// 'fk_object_persistence_method_id'=>NULL
					// 'fk_datamanagement_process_id'=>NULL
				];
	
			}

		}


		if(count($bulkInsertArray)>0){
			$columnNameArray=['ref_fk_object_id_1','ref_fk_object_type_id_1','ref_fk_object_id_2','ref_fk_object_type_id_2'];
			
			$tableName = "map_object_2_object";
			// below line insert all your record and return number of rows inserted
			$insertCount = Yii::$app->db->createCommand()
						   ->batchInsert(
								 $tableName, $columnNameArray, $bulkInsertArray
							 )
						   ->execute();
		}

		// VarDumper::dump("Inserted rows: ". $insertCount);

		return $this->actionIndex($from_object_id=$from_id, $from_object_type_id=$from_fk_object_type_id);


	}
	//  ...}

}