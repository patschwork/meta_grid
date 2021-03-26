<?php

namespace app\controllers;

use app\models\Project;
use app\models\Client;
use app\models\DbDatabase;
use Yii;
use yii\filters\VerbFilter;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;

class ImportController extends \yii\web\Controller
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
                        'roles' => ['author', 'global-create', 'create' ."-" . 'dbtable'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || (Yii::$app->User->can('create-dbtable') && Yii::$app->User->can('create-dbtablefield'))) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],			
        ];
    }

    private function getProjectList()
	{
		// autogeneriert ueber gii/CRUD
		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		$projectList = array();
		foreach($projects as $project)
		{
			$projectList[$project->id] = $project->name;
		}
		return $projectList;
    }
    
    protected function import_templates()
    {
        $array_templates = array();
        $array_templates_head[1]["name"] = "INFORMATION_SCHEMA.COLUMNS";
        $array_templates_head[1]["header"] = true;
        $array_templates_head[1]["seperator"] = "\t";
        $array_templates_head[1]["staging_table_meta_grid"] = "ImportStageDbTable";
        
        $array_templates_map_incoming = array();
        $array_templates_map_incoming[1][0]  = "db_table_name";
        $array_templates_map_incoming[1][1]  = "db_table_description";
        $array_templates_map_incoming[1][2]  = "db_table_field_name";
        $array_templates_map_incoming[1][3]  = "db_table_field_datatype";
        $array_templates_map_incoming[1][4]  = "db_table_field_description";
        $array_templates_map_incoming[1][5]  = "db_table_type_name";
        $array_templates_map_incoming[1][6]  = "db_table_context_name";
        $array_templates_map_incoming[1][7]  = "db_table_context_prefix";
        $array_templates_map_incoming[1][8]  = "isPrimaryKeyField";
        $array_templates_map_incoming[1][9]  = "isForeignKeyField";
        $array_templates_map_incoming[1][10] = "foreignKey_table_name";
        $array_templates_map_incoming[1][11] = "foreignKey_table_field_name";
		$array_templates_map_incoming[1][12] = "is_BusinessKey";
		$array_templates_map_incoming[1][13] = "is_GDPR_relevant";
		$array_templates_map_incoming[1][14] = "location";
		$array_templates_map_incoming[1][15] = "database_or_catalog";
		$array_templates_map_incoming[1][16] = "schema";
		$array_templates_map_incoming[1][17] = "fk_project_id";
		$array_templates_map_incoming[1][18] = "fk_db_database_id";
		$array_templates_map_incoming[1][19] = "column_default_value";
		$array_templates_map_incoming[1][20] = "column_cant_be_null";
		$array_templates_map_incoming[1][21] = "additional_field_1";
		$array_templates_map_incoming[1][22] = "additional_field_2";
		$array_templates_map_incoming[1][23] = "additional_field_3";
		$array_templates_map_incoming[1][24] = "additional_field_4";
		$array_templates_map_incoming[1][25] = "additional_field_5";
		$array_templates_map_incoming[1][26] = "additional_field_6";
		$array_templates_map_incoming[1][27] = "additional_field_7";
		$array_templates_map_incoming[1][28] = "additional_field_8";
		$array_templates_map_incoming[1][29] = "additional_field_9";

        // INFORMATION_SCHEMA.COLUMNS
        $array_templates_map_importtable = array();
        $array_templates_map_importtable[1][0] = "table_name";
        $array_templates_map_importtable[1][2] = "column_name";
        $array_templates_map_importtable[1][3] = "data_type";
        $array_templates_map_importtable[1][15] = "table_catalog";
        $array_templates_map_importtable[1][16] = "table_schema";
        $array_templates_map_importtable[1][21] = "is_nullable";
        $array_templates_map_importtable[1][22] = "numeric_precision";
        $array_templates_map_importtable[1][23] = "numeric_scale";
        $array_templates_map_importtable[1][24] = "datetime_precision";
        $array_templates_map_importtable[1][25] = "character_maximum_length";

        $array_templates_return = array();
        $array_templates_return['head'] = $array_templates_head;
        $array_templates_return['map_incoming'] = $array_templates_map_incoming;
        $array_templates_return['map_importtable'] = $array_templates_map_importtable;
        return $array_templates_return;
    }

    public function actionIndex()
    {
        $model = new \app\models\ImportForm();
    
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here

                $array_csv = array();
                $lines = explode("\n", $model->pastedValues);
                $i = 0;
                foreach ($lines as $line)
                {
                    $i++;
                    $array_csv[] = str_getcsv($line, "\t");
                }
                Yii::trace("$i lines to process", "actionIndex");
                $template_id = $model->import_template_id;
                $fk_project_id = $model->fk_project_id;
                $replace_string_to_null = $model->replace_string_to_null;
                $use_header = true;

                $this->mapValues($template_id, $fk_project_id,  $array_csv, $this->import_templates(), $use_header, $replace_string_to_null);
                return;
            }
        }
    
        // generate list of templates
        $template_list = array();
        
        foreach($this->import_templates()['head'] as $id=>$header_elements)
        {
            $template_list[$id] = $header_elements["name"];
        }

        return $this->render('index', [
            'model' => $model,
            'projectList' => $this->getProjectList(),
            'templateList' => $template_list,
        ]);
    }


    protected function mapValues($template_id, $fk_project_id, $array_csv, $import_templates, $use_header, $replace_string_to_null)
    {
		$app_config_import_processing_time_limit = \vendor\meta_grid\helper\Utils::get_app_config("import_processing_time_limit");
		$app_config_import_processing_memory_limit = \vendor\meta_grid\helper\Utils::get_app_config("import_processing_memory_limit");
		set_time_limit($app_config_import_processing_time_limit);
		ini_set('memory_limit', $app_config_import_processing_memory_limit."M");

        $import_datetime = date("Y-m-d H:i:s");
        Yii::trace("import_datetime = $import_datetime", "mapValues");

        if ($template_id == 1)
        {
            if ($replace_string_to_null == NULL)
            {
                $replace_string_to_null = '<null>'; // SQuirreL Copy&Paste artefact ;-)
            }
        }

        $project_model = Project::findOne($fk_project_id);
        $client_model = Client::findOne($project_model->fk_client_id);

        $loadData = array();
        $attrData = array();
        $importColumnIdMap = array();
        foreach($import_templates["map_incoming"][$template_id] as $key=>$value)
        {
            if (array_key_exists($key, $import_templates["map_importtable"][$template_id])) { 
                $attrData[$value] = $import_templates["map_importtable"][$template_id][$key];
            }
        }

        if ($use_header)
        {
            $staging_table_meta_grid = $import_templates["head"][$template_id]["staging_table_meta_grid"];
            foreach($array_csv[0] as $import_index=>$header_name)
            {
                foreach($attrData as $table_field_name=>$mapped_field)
                {
                    // if ($header_name === $mapped_field)
                    if (strtoupper($header_name) === strtoupper($mapped_field))
                    {
                        $importColumnIdMap[$import_index] = $table_field_name;
                    }
                }
            }

            $i_loadrow = 0;
            // now process import data
            foreach($array_csv as $row=>$subArray)
            {
                if ($row === 0)
                {
                    continue;
                }

                foreach($importColumnIdMap as $id=>$table_name)
                {
                    if (array_key_exists($id, $subArray)) { 

                        // $replace_string_to_null
                        $value = $subArray[$id];
                        if ($replace_string_to_null !== NULL)
                        {
                            if ($value === $replace_string_to_null)
                            {
                                $value = NULL;
                            }
                        }
                        $loadData[$row][$staging_table_meta_grid][$table_name] =$value; //""; //
                        $i_loadrow++;
                    }
                }
            }
        }

        Yii::trace("Mapped rows = $i_loadrow", "mapValues");

        $i_saved_rows = 0;

        foreach($loadData as $modelData)
        {
            $modelData_transformed = $this->import_templates_transformations($template_id, $modelData, $staging_table_meta_grid, $fk_project_id);
            $model2 = new \app\models\base\ImportStageDbTable();
            $model2->load($modelData_transformed);
            $model2->project_name = $project_model->name;
            $model2->client_name = $client_model->name;
            $model2->_import_date = $import_datetime;
            $model2->_import_state = 0;
            if ($model2->save())
            {
                $i_saved_rows++;
            }
        }
        Yii::trace("Saved rows to table import_stage_db_table = $i_saved_rows", "mapValues");
        echo "Data loaded into Table import_stage_db_table";
        return $this->redirect(['importstagedbtable/index']);
    }

    protected function import_templates_transformations($template_id, $row_array, $staging_table_meta_grid, $fk_project_id)
    {
        if ($template_id == 1)
        {
            if (array_key_exists("database_or_catalog", $row_array[$staging_table_meta_grid]))
            {
                if (array_key_exists("schema", $row_array[$staging_table_meta_grid]))
                {
                    if (array_key_exists("db_table_name", $row_array[$staging_table_meta_grid]))
                    {
                        $row_array[$staging_table_meta_grid]["location"] = 
                            '"' . $row_array[$staging_table_meta_grid]["database_or_catalog"] . '"' . '.' .
                            '"' . $row_array[$staging_table_meta_grid]["schema"]              . '"' . '.' .
                            '"' . $row_array[$staging_table_meta_grid]["db_table_name"] . '"';
                    }                   
                }
            }
            // is_nullable
            if (array_key_exists("additional_field_1", $row_array[$staging_table_meta_grid]))
            {
                if ($row_array[$staging_table_meta_grid]["additional_field_1"] === "YES")
                {
                    $row_array[$staging_table_meta_grid]["column_cant_be_null"] = false;
                }
            }
            
            if (!array_key_exists("db_table_description", $row_array[$staging_table_meta_grid]))
            {
                $row_array[$staging_table_meta_grid]["db_table_description"] = "(Added via Copy&Paste Import)";
            }
            if (!array_key_exists("db_table_field_description", $row_array[$staging_table_meta_grid]))
            {
                $row_array[$staging_table_meta_grid]["db_table_field_description"] = "(Added via Copy&Paste Import)";
            }                        
           
            // numeric_precision and numeric_scale
            if (array_key_exists("additional_field_2", $row_array[$staging_table_meta_grid]))
            {
                if (array_key_exists("additional_field_3", $row_array[$staging_table_meta_grid]))
                {
                    if (($row_array[$staging_table_meta_grid]["additional_field_2"] !== NULL) && ($row_array[$staging_table_meta_grid]["additional_field_3"]) !== NULL)
                    {
                        $row_array[$staging_table_meta_grid]["db_table_field_datatype"] = $row_array[$staging_table_meta_grid]["db_table_field_datatype"] . 
                            "(" . $row_array[$staging_table_meta_grid]["additional_field_2"] . ", " . $row_array[$staging_table_meta_grid]["additional_field_3"] . ")";
                    }
                }
            }

            // character_maximum_length
            if (array_key_exists("additional_field_5", $row_array[$staging_table_meta_grid]))
            {
                if ($row_array[$staging_table_meta_grid]["additional_field_5"] !== NULL)
                {
                    $row_array[$staging_table_meta_grid]["db_table_field_datatype"] = $row_array[$staging_table_meta_grid]["db_table_field_datatype"] . 
                        "(" . $row_array[$staging_table_meta_grid]["additional_field_5"] . ")";
                }
            }
            
            if (!array_key_exists("fk_db_database_id", $row_array[$staging_table_meta_grid]))
            {   
                $chkValue = $row_array[$staging_table_meta_grid]["database_or_catalog"];
                if (($chkValue !== NULL) && ($chkValue !== ""))
                {
                    //$modelDbDatabase = DbDatabase::findOne(["name" => $chkValue, "fk_project_id" => $fk_project_id]);
                    $modelDbDatabase = DbDatabase::find()->where(["name" => $chkValue, "fk_project_id" => $fk_project_id])->one(); // Workaround T120...
                    if ($modelDbDatabase !== NULL)
                    {
                        $row_array[$staging_table_meta_grid]["fk_db_database_id"] = $modelDbDatabase->id;
                    }
                }
            }

            if (!array_key_exists("fk_project_id", $row_array[$staging_table_meta_grid]))
            { 
                $row_array[$staging_table_meta_grid]["fk_project_id"] = $fk_project_id;
            }
        }
        return $row_array;
    }
}
