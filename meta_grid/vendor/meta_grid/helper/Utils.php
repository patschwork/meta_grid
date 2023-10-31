<?php

namespace vendor\meta_grid\helper;

use app\models\DbTableField;
use yii\db\Query;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\VTag2ObjectList;


/**
 * Util helper for meta#grid
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 2.4.1
 * 
 */
class Utils
{
    /**
     * Gets the application config parameter from the database table app_config
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 2.4.1
     * 
     */

     public function get_app_config($key)
     {

        $default_value = null;

        if ($key=="liquibase_changelog_master_filepath") $default_value="../../../../database_model/liquibase/db.changelog-master.xml";
        if ($key=="web_app_header_bckcolor") $default_value="";
        if ($key=="web_app_header_brandlabel_additional_text") $default_value="";
        if ($key=="db_table_show_buttons_for_different_object_type_updates") $default_value=0;
        if ($key=="floatthead_for_gridviews") $default_value=1;
        if ($key=="cache_duration_mappings_list") $default_value=3600*48;
        if ($key=="globalsearch_init_empty") $default_value=1;
        if ($key=="importstage_dbtable_processing_time_limit") $default_value=2000;
        if ($key=="importstage_dbtable_processing_memory_limit") $default_value=1024;
        if ($key=="import_processing_time_limit") $default_value=2000;
        if ($key=="import_processing_memory_limit") $default_value=1024;
        if ($key=="mapper_createext_time_limit") $default_value=2000;
        if ($key=="mapper_createext_memory_limit") $default_value=1024;
        if ($key=="dbtablefield_time_limit") $default_value=2000;
        if ($key=="dbtablefield_memory_limit") $default_value=1024;

        $res_arr = ((new Query())->from('app_config')->select(['valueSTRING', 'valueINT'])->where(["key" => $key])->one());      

        if (! $res_arr)
        {
            return $default_value;
        }

        $valueSTRING = $res_arr['valueSTRING'];
        $valueINT = $res_arr['valueINT'];

        if ((is_null($valueSTRING) ||  $valueSTRING === "" || empty($valueSTRING)) && (is_null($valueINT)))
        {
            return $default_value;
        }
        else
        {
            if (is_null($valueINT))
            {
                return $valueSTRING;
            }
            else
            {
                return intval($valueINT);
            }
        }

        return null;
     }

     public function DBisReadOnly($database_driver_name, $database_source_path)
     {
         $returnValue = null;
         if ($database_driver_name == "sqlite")
         {
             try
             {
                 $connection = \Yii::$app->db;
                 if (is_writable($database_source_path))
                 {
                     $returnValue = false;
                 }
                 else
                 {
                     $returnValue = true;
                 }
             }
             catch (\yii\db\Exception $e)
             {
                 $returnValue = true;
             }
         }
         return $returnValue;
     }

    /**
     * This function returns all controller and public actions
     * 
     * @param curlstatus: True or False -> Return curl-status informateion (makes it slower)
     * @param filterOn: String -> Filters on specific actionnames. Default is "index"
     * 
     * @return array: Elements are controllers and actions. Can be used in Url::to(array(key))
     * 
     * Helpful links: https://stackoverflow.com/questions/27912988/retrieving-array-of-controllers-actions
     * Additional helpful: https://stackoverflow.com/questions/11797680/getting-http-code-in-php-using-curl
     */
	public function getAllControllerActions($curlstatus = False, $filterOn = "index", $permissionstatus = False)
	{
		$controllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@app/controllers'), ['recursive' => true]);
		$actions = [];
		foreach ($controllers as $controller) {
			$contents = file_get_contents($controller);
			$controllerId = Inflector::camel2id(substr(basename($controller), 0, -14));
			preg_match_all('/public function action(\w+?)\(/', $contents, $result);
			foreach ($result[1] as $action) {
				$actionId = Inflector::camel2id($action);
				$route = $controllerId . '/' . $actionId;

				if ($actionId !== $filterOn && $filterOn != NULL)
				{
					continue;
				}

                
				$output = $controllerId; // Default
				if ($curlstatus)
				{
                    $url = Yii::$app->request->hostInfo . Url::to([$route]);
                    $ch = curl_init($url);
					curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
					curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_TIMEOUT,10);
					$output = curl_exec($ch);
					$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
				}
                if ($permissionstatus)
                {
                    $output = Yii::$app->User->can('view-' . $controllerId);
                }
				$actions[$route] = $output;
			}
		}
		asort($actions);
		return $actions;
	}

    /**
     * This function creates a list of all controllers in @app/controllers and returns an array
     * 
     * @return array $array[controllerid] = '\app\controllers\AttributeController' ....
     */
    public function getAllControllers()
    {
        $returnValues = array();
        $path = '@app/controllers';
        $namespace = str_replace(DIRECTORY_SEPARATOR,'\\',str_replace('@','',$path));
        $controllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias($path), ['recursive' => true]);
        foreach($controllers as $key=>$contollerfilefullpath)
        {
            $controllerId = Inflector::camel2id(substr(basename($contollerfilefullpath), 0, -14));
            $pathelements = explode(DIRECTORY_SEPARATOR, $contollerfilefullpath);
            $class_filename = $pathelements[count($pathelements)-1];
            $class_filename_withoout_extenstion = str_replace(".php", "", $class_filename);
            $returnValues[$controllerId] = '\\'.$namespace.'\\'.$class_filename_withoout_extenstion;
        }
        return $returnValues;
    }

    /**
     * This function creates a list of all controllerId's with the information, if it has a public method with the name "registerControllerRole"
     * 
     * @return array $array[controllerid] = true or false (true, if method was found)
     */
    public function eval_if_controller_has_method_registerControllerRole()
    {
        $returnValues = array();
        $controllerlist = $this->getAllControllers();
        foreach($controllerlist as $controllerid=>$controllerclass)
        {
            $methods = get_class_methods($controllerclass);
            if (in_array("registerControllerRole", $methods))
            {
                $returnValues[$controllerid] = true;
            }
            else
            {
                $returnValues[$controllerid] = false;
            }
        }
        return $returnValues;
    }

    /**
     * Prepare Menu items for the sidebar in AdminLTE, sorting, with header.
     * Could be moved to the database in the future.
     */
    public function prepare_adminlte_widgets_Menu_items()
    {
        $routes = $this->getAllControllerActions();
        $menu = array();
        // $menu["navbar"]["dummy"]["dummy"]["dummy"]="";
        foreach ($routes as $action=>$controller)
        {
            $header = "ETC";
            $sort = 999;
            $label = Yii::t('app',\yii\helpers\Inflector::pluralize(ucfirst($controller)));
            $icon = "th";
            $menuposition = "sidebar-left";
            
            if ((!Yii::$app->user->isGuest && Yii::$app->User->can('view-' . $controller)))
            {
                if ($action == "datatransferprocess/index")  {$sort = 1; $header = strtoupper(Yii::t('app',"Data Engineering"));  $label = Yii::t('app',"ETL Jobs");           $icon = "file-code";}
                if ($action == "datadeliveryobject/index")   {$sort = 2; $header = strtoupper(Yii::t('app',"Presentation"));      $label = Yii::t('app',"Reports");            $icon = "chart-line";}
                if ($action == "scheduling/index")           {          $icon = "clock";}
                if ($action == "keyfigure/index")            {          $icon = "calculator";}
                if ($action == "glossary/index")             {          $icon = "brain";}
                if ($action == "contact/index")              {          $icon = "address-card";}
                if ($action == "contactgroup/index")         {          $icon = "address-book";}
                if ($action == "bracket/index")              {          $icon = "satellite";}
                if ($action == "url/index")                  {          $icon = "link";}
                if ($action == "attribute/index")            {          $icon = "info";}

                if ($action == "dbdatabase/index")           {$sort = 3; $header = strtoupper(Yii::t('app',"Architecture"));      $label = Yii::t('app',"Databases");          $icon = "database";}
                if ($action == "dbtable/index")              {$sort = 3; $header = strtoupper(Yii::t('app',"Architecture"));      $label = Yii::t('app',"Tables/Fields");      $icon = "table";}
                if ($action == "sourcesystem/index")         {$sort = 3; $header = strtoupper(Yii::t('app',"Architecture"));      $label = Yii::t('app',"Sources");            $icon = "server";}
                if ($action == "objectcomment/index")        {$header = strtoupper(Yii::t('app',"Special"));}
                if ($action == "mapper/index")               {$header = strtoupper(Yii::t('app',"Special"));}
                if ($action == "objectdependson/index")      {$header = strtoupper(Yii::t('app',"Special"));}
                
                // do not show, therefor in a dummy group ;-)
                if ($action == "dbtablefield/index")         {$menuposition = "do-not-show"; }
                if ($action == "global-search/index")        {$menuposition = "do-not-show"; }
                if ($action == "vtag2objectlist/index")      {$menuposition = "do-not-show"; }

                // navbar
                if ($action == "client/index")               {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Organisation"));      $label = Yii::t('app',"Clients");       $icon = "building";}
                if ($action == "project/index")              {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Organisation"));      $label = Yii::t('app',"Projects");      $icon = "clipboard";}
                if ($action == "dbtablecontext/index")       {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Misc."));             $icon = "box";}
                if ($action == "tool/index")                 {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Misc."));             $icon = "toolbox";}
                if ($action == "tag/index")                  {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Misc."));             $icon = "tags";}
                // everything with *type
                if (strpos($action, 'type/index') !== false) {$menuposition = "navbar"; $sort = 1; $header = strtoupper(Yii::t('app',"Misc."));             $icon = "hammer";}
                
                
                $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
                $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
                $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
            }
        }

        
        if (Yii::$app->User->identity == NULL ? False : Yii::$app->User->identity->isAdmin)
        {
            $header = "ADMIN";
            $menuposition = "navbar";
            $action = "/user/admin/index";
            $controller = "user";
            $label = Yii::t("usuario", "Manage users");
            $icon = "user";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        } 

        if ((!Yii::$app->user->isGuest && (Yii::$app->User->can('create-dbtable') && Yii::$app->User->can('create-dbtablefield')))) // same as in ImportController/behaviors()
        {
            $header = Yii::t("app","IM-/EXPORT");
            $menuposition = "navbar";
            $action = "/import/index";
            $controller = "import";
            $label = Yii::t("app","Import via Copy & Paste");
            $icon = "file-import";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        } 
        
        if ((!Yii::$app->user->isGuest && (Yii::$app->User->can('view-dbtable')))) // same as in DbTableController/behaviors()
        {
            $header = Yii::t("app","IM-/EXPORT");
            $menuposition = "navbar";
            $action = "/dbtable/export_csv";
            $controller = "dbtable";
            $label = Yii::t("app","Export all tables as CSV");
            $icon = "file-export";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        } 
        
        if ((!Yii::$app->user->isGuest && (Yii::$app->User->can('view-dbtablefield')))) // same as in DbtablefieldController/behaviors()
        {
            $header = Yii::t("app","IM-/EXPORT");
            $menuposition = "navbar";
            $action = "/dbtablefield/export_csv";
            $controller = "dbtablefield";
            $label = Yii::t("app","Export all tables with fields as CSV");
            $icon = "file-export";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        } 
        
        if ((!Yii::$app->user->isGuest && (Yii::$app->User->can('view-keyfigure')))) // same as in KeyfigureController/behaviors()
        {
            $header = Yii::t("app","IM-/EXPORT");
            $menuposition = "navbar";
            $action = ["keyfigure/export","format"=>"XML", "what"=>""];
            $controller = "keyfigure";
            $label = Yii::t("app","Export all keyfigures as XML");
            $icon = "file-export";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
            
            $action = ["keyfigure/export","format"=>"JSON", "what"=>""];
            $controller = "keyfigure2";
            $label = Yii::t("app","Export all keyfigures as JSON");
            $icon = "file-export";

            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        } 
        
        if (YII_ENV_DEV)
        {
            $header = "DEV";
            $menuposition = "navbar";

            $action = "/gii";
            $controller = "gii";
            $label = "Gii";
            $icon = "file-code";
            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;

            $action = "/debug";
            $controller = "debug";
            $label = "Debug";
            $icon = "bug";
            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;

            $action = "/translatemanager";
            $controller = "translatemanager";
            $label = "Translatemanager";
            $icon = "language";
            $menu[$menuposition][$sort][$header][$controller]["label"] = $label;
            $menu[$menuposition][$sort][$header][$controller]["icon"] = $icon;
            $menu[$menuposition][$sort][$header][$controller]["action"] = $action;
        }


        return $menu;
    }

	public function adminlte_widgets_Navbar_items()
	{
		$returnValue = "";

        $menuSorted = $this->prepare_adminlte_widgets_Menu_items();
        if (array_key_exists('navbar', $menuSorted))
        {
            ksort($menuSorted["navbar"]);
            foreach ($menuSorted["navbar"] as $sortkey=>$menu)
            {
                foreach($menu as $header=>$controllers)
                {
                    $mainItemName = $header;
                    $returnValue .= '<li class="nav-item dropdown">';
                    $returnValue .= '<a id="'. $mainItemName . 'SubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">' . $mainItemName . '</a>';
                    $returnValue .= '<ul aria-labelledby="' . $mainItemName . 'SubMenu1" class="dropdown-menu border-0 shadow">';
                    
                    foreach($controllers as $controller=>$menuproperties)
                    {
                        $returnValue .= '<li>';
                        $icon = $menuproperties["icon"];
                        // $label = $languageItem->name . (Yii::$app->language == $languageItem->language_id ? ' <span style="color: #80ced6;" class="glyphicon glyphicon-ok-sign"></span>' : "");
                        $label = '<i class="nav-icon fas fa-' . $icon . '"></i> ' . $menuproperties["label"];
                        if (is_array($menuproperties["action"])) $url = $menuproperties["action"];
                        else $url = ["/" . $menuproperties["action"]];
                        $returnValue .= Html::a($label, $url, ['data-method' => 'post', 'class' => 'dropdown-item']);
                        $returnValue .= '</li>';
    
                    }
    
                    $returnValue .= '</ul>';
                    $returnValue .= '</li>';
                }
            }
        }

		return $returnValue;		
	}

    /**
     * Return items to use as AdminLTE3 Menu items
     * Example
     *       [       'label' => Yii::t('app', 'Sources')
     *              ,'icon' => 'th'
     *           // , 'badge' => '<span class="right badge badge-danger">New</span>'
     *              , 'visible' => !Yii::$app->user->isGuest
     *              , 'url' => ['/sourcesystem']
     *           // , 'target' => '_blank'
     *       ],
     */
    public function adminlte_widgets_Menu_items()
    {
        $menuSorted = $this->prepare_adminlte_widgets_Menu_items();
        $returnArray = array();
        if (array_key_exists('sidebar-left', $menuSorted))
        {
            ksort($menuSorted["sidebar-left"]);
            foreach ($menuSorted["sidebar-left"] as $sortkey=>$menu)
            {
                ksort($menu);
                foreach($menu as $header=>$controllers)
                {
                    array_push($returnArray, ['label' => $header, 'header' => true]);
                    foreach($controllers as $controller=>$menuproperties)
                    {
                        $item = [
                             'label' => $menuproperties["label"]
                             ,'icon' => $menuproperties["icon"]
                             ,'visible' => (!Yii::$app->user->isGuest && Yii::$app->User->can('view-' . $controller))
                             ,'url' => ["/" . $menuproperties["action"]]
                            ];
                        array_push($returnArray, $item);
                    }
                }
            }
        }

        return $returnArray;
    }

    /**
     * This is used to prepare the list of tags in the left sidebar
     * Only tags the user may see/work with are displayed.
     * The header will only be shown, if a tag will be displayed.
     */
    public function adminlte_widgets_Menu_items_tags()
    {


        $returnArray = array();
        $count = 0;
        if (!Yii::$app->user->isGuest)
        {
            array_push($returnArray, ['label' => Yii::t('app','TAGS'), 'header' => true]);
            $RBACHelper = new \vendor\meta_grid\helper\RBACHelper();		
            $allTagsTheUserCanSee = $RBACHelper->getAllTagsTheUserCanSee();
    
            foreach ($allTagsTheUserCanSee as $key=>$value)
            {
                $count++;
                $label = $value["tag_name"];
                $url = Url::to(['vtag2objectlist/index']);
                $url .= "&VTag2ObjectListSearch"."[fk_tag_id]=".$value["fk_tag_id"];
                $item = [
                    'label' => $label
                    ,'iconStyle' => 'far'
                    ,'iconClassAdded' => $value["optgroup"] == 0 ? 'text-info' : ($value["optgroup"] == 1 ? 'text-danger' : "text-warning")
                    ,'visible' => (!Yii::$app->user->isGuest)
                    ,'url' => $url
                ];
                array_push($returnArray, $item);
            }
        }
        if ($count==0)
        {
            $returnArray = array();
        }        

        return $returnArray;
    }

	public function getAllTagsTheUserCanSeeCounted($user_id = NULL, $filterByTags = array())
	{
        if (!\Yii::$app->user->isGuest)
        {
            $user_id = $user_id == NULL ? Yii::$app->user->id : $user_id;
            $permProjectsCanSeeByUserId = array();
            if (!\Yii::$app->user->isGuest)
            {
                $permProjectsCanSeeByUserId = Yii::$app->User->identity->getPermProjectsCanSeeByUserId($user_id);
            }
            
            $dependency = new \yii\caching\DbDependency();
            $dependency->sql="SELECT max(log_datetime) FROM map_object_2_tag_log";
    
            $VTagsOptGroup_Model = VTag2ObjectList::find()->cache(NULL, $dependency)
            ->select(["fk_tag_id", "tag_name", "COUNT(*) AS cnt"])
            ->where(["fk_user_id"=>$user_id])
            ->orWhere(["optgroup"=>0])
            ->orWhere(["in", "fk_project_id", $permProjectsCanSeeByUserId])
            // ->andWhere(["tag_name"=>"Needs review"])
            ->groupBy(["fk_tag_id", "tag_name"])
            ->createCommand()->queryAll()
            ;
            return $VTagsOptGroup_Model;
        }
        return array();
	}

	public function getAllDbTableFieldsTheUserCanSeeCounted($user_id = NULL, $filterByTags = array())
	{
		$user_id = $user_id == NULL ? Yii::$app->user->id : $user_id;
		$permProjectsCanSeeByUserId = array();
		if (!\Yii::$app->user->isGuest)
		{
            $permProjectsCanSeeByUserId = Yii::$app->User->identity->getPermProjectsCanSeeByUserId($user_id);
            $DbTableField_Model = DbTableField::find()
            ->select(["COUNT(*) AS cnt"])
            ->orWhere(["in", "fk_project_id", $permProjectsCanSeeByUserId])
            // ->andWhere(["tag_name"=>"Needs review"])
            // ->groupBy(["id"])
            ->createCommand()->queryAll()
            ;
            return $DbTableField_Model;
		}
		return NULL;
	}

    public function breadcrumb_project_or_client($model)
    {
        $modelname=get_class($model);
        $modelname_exl = explode("\\", $modelname);
        $modelname_exl_cnt = count($modelname_exl);
        $modelname_basename = $modelname_exl[$modelname_exl_cnt-1];
        $modelname_basename_search = $modelname_basename."Search";
        if (in_array("fk_project_id", $model->attributes())) return ['label' => $model->fkProject->name, 'url' => ['index', $modelname_basename_search."[fk_project_id]"=>$model->fk_project_id]];
        else
        {
            if (in_array("fk_client_id", $model->attributes())) return ['label' => $model->fkClient->name, 'url' => ['index', $modelname_basename_search."[fk_client_id]"=>$model->fk_client_id]];
            else return null;
        }
    }
}