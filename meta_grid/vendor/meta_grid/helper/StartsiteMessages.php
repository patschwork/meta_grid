<?php

namespace vendor\meta_grid\helper;

use Yii;
use yii\bootstrap4\Alert;

/**
 * All messages for the main start page for meta#grid
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 3.0
 * 
 */
class StartsiteMessages
{

    /**
     * Inform the non-Guest user about an update for meta#grid.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function NewMetaGridVersionCheck()
    {
        if (!Yii::$app->user->isGuest)
        {
            $ApplicationVersion = new \vendor\meta_grid\helper\ApplicationVersion();
            $newVersionAvaiable = $ApplicationVersion->isNewApplicationAvailable();
            
            if ($newVersionAvaiable)
            {
                $msg = Yii::t('', 'A new Version {newVersion} of {appName} is available. Your current version is {currentVersion}.<br><br>Read the <a class="btn btn-primary" href="{linkToDoc}" target="_blank">documentation</a> how to update the application.<br><br><button class="btn btn-warning" onclick="hideMeSomeDays()">Hide this message for 3 days</button>'
                        ,['newVersion' => $ApplicationVersion->getCookieNewerVersion(), 'currentVersion' => $ApplicationVersion->getVersion(), 'linkToDoc' => 'https://blog.meta-grid.com/category/docs/update', 'appName' => $ApplicationVersion->getApplicationName()]);
                echo Alert::widget([
                    'options' => [
                            'class' => 'alert-info',
                            'name' => 'new-version-info-start',
                    ],
                    'body' => $msg,
                ]);
            }
        }
    }

    /**
     * Inform the non-Guest user that several essential objecttypes are empty. Most cases are an empty database (beginning with meta#grid)
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function EmptyObjectTypes()
    {
        if (!Yii::$app->user->isGuest)
        {
            $foundIssues=0;
            $dbtablecontextCount = (new yii\db\Query())->from('db_table_context')->count();
            $dbtabletypeCount = (new yii\db\Query())->from('db_table_type')->count();
            $toolCount = (new yii\db\Query())->from('tool')->count();
            $tooltypeCount = (new yii\db\Query())->from('tool_type')->count();
            $datatransfertypeCount = (new yii\db\Query())->from('data_transfer_type')->count();
            $datadeliverytypeCount = (new yii\db\Query())->from('data_delivery_type')->count();
            
            $foundIssues = $dbtablecontextCount==0 		? $foundIssues + 1 : $foundIssues + 0;
            $foundIssues = $dbtabletypeCount==0 		? $foundIssues + 1 : $foundIssues + 0;
            $foundIssues = $toolCount==0 				? $foundIssues + 1 : $foundIssues + 0;
            $foundIssues = $tooltypeCount==0 			? $foundIssues + 1 : $foundIssues + 0;
            $foundIssues = $datatransfertypeCount==0 	? $foundIssues + 1 : $foundIssues + 0;
            $foundIssues = $datadeliverytypeCount==0 	? $foundIssues + 1 : $foundIssues + 0;
            
            $alertBody = "<br>";
            $alertBody .= $dbtabletypeCount==0 		? '<b>'.Yii::t('app','Table Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtabletype') . '">here</a>.<br>' : "";
            $alertBody .= $dbtablecontextCount==0 	? '<b>'.Yii::t('app','Table Context').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtablecontext') . '">here</a>.<br>' : "";
            $alertBody .= $tooltypeCount==0 		? '<b>'.Yii::t('app','Tool Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tooltype') . '">here</a>.<br>' : "";
            $alertBody .= $toolCount==0 			? '<b>'.Yii::t('app','Tool').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tool') . '">here</a>.<br>' : "";
            $alertBody .= $datatransfertypeCount==0 ? '<b>'.Yii::t('app','Data Transfer Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('datatransfertype') . '">here</a>.<br>' : "";
            $alertBody .= $datadeliverytypeCount==0 ? '<b>'.Yii::t('app','Data Delivery Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('datadeliverytype') . '">here</a>.<br>' : "";
            
            if ($foundIssues>0)
            { 
                echo Alert::widget([
                        'options' => [
                                'class' => 'alert-warning',
                        ],
                        'body' => Yii::t('app','Following objecttypes does not have any items. They are basically needed for further working. You should start here.').$alertBody,
                ]);
            }
        }
    }

    /**
     * Inform all user that a new role was created.
     * This is a developer hint.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function NewRoleCreated()
    {
        $session = Yii::$app->session;
        if ($session->hasFlash('new_role_created'))
        {	
            echo Alert::widget([
                    'options' => [
                                    'class' => 'alert-info',
                    ],
                    'body' => $session->getFlash('new_role_created'),
            ]);
        }	
    }

    /**
     * Inform admin user that the liquibase database structure differs from acutal deployed database version.
     * This is mostly a developer hint.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function LiquiBaseVersion()
    {
        try
        {
            $Utils = new \vendor\meta_grid\helper\Utils();
            // get the path from the app_config database table. If not exists, then use a default.
            $app_config_liquibase_changelog_master_filepath = $Utils->get_app_config("liquibase_changelog_master_filepath");
        
            // reads the LiquiBase Changelog-Master-XML File
            $xml=simplexml_load_file($app_config_liquibase_changelog_master_filepath) or die("Error: Cannot create object");
            $lastChangelogFileXMLElement = $xml->include[count($xml->include)-1]['file'];
            // Example entry in xml file:   <include relativeToChangelogFile="true" file="changesets/0000036/changelog.xml"/>  <!-- SQL ALTER TABLE | data_transfer_process;data_transfer_process_log -->
        
            // manipulate string for comparing with database entry
            $lastChangelogFileXMLElementReadyForComparing = str_replace("changesets/","",$lastChangelogFileXMLElement);
        
            // get last deployment from database
            $lastChangelogFileDeployedInDB = "";
            $res = (new yii\db\Query())->select(['FILENAME'])->from('DATABASECHANGELOG')->orderBy("ORDEREXECUTED DESC")->limit(1)->one();
            foreach($res as $key => $value ){
                $lastChangelogFileDeployedInDB = $value;
                // Example entry in table:   000036/changelog.xml
            }
        
            if (strlen(explode("/",$lastChangelogFileDeployedInDB)[0]) < strlen(explode("/",$lastChangelogFileXMLElementReadyForComparing)[0]))
            {
                // workaround: the folder is missing a 0-digit :-(
                $lastChangelogFileXMLElementReadyForComparing = substr(explode("/",$lastChangelogFileXMLElementReadyForComparing)[0],1,strlen(explode("/",$lastChangelogFileDeployedInDB)[0])) . "/" .  explode("/",$lastChangelogFileXMLElementReadyForComparing)[1];
            }
        
            // compare the results
            if ($lastChangelogFileDeployedInDB != $lastChangelogFileXMLElementReadyForComparing) {
                // $foundIssues = $foundIssues + 1;
                $alertBody2 = '<b>'.Yii::t('app','The database structure version differs from available version. Please update the database!').'</b><br>Found version in database: ' . $lastChangelogFileDeployedInDB . '<br>Found deployable version: ' . $lastChangelogFileXMLElementReadyForComparing;
                echo Alert::widget([
                    'options' => [
                            'class' => 'alert-warning',
                    ],
                    'body' => $alertBody2,
                ]);
            }
        }
        catch (\Exception $e) {
            // Ignore any errors...
        
            // if admin user then show a hint:
            if (isset(Yii::$app->user->identity->isAdmin))
            {
                if (Yii::$app->user->identity->isAdmin) 
                {
                    if ($app_config_liquibase_changelog_master_filepath != "")
                    {
                        echo Alert::widget([
                            'options' => [
                                    'class' => 'alert-danger',
                            ],
                            'body' => "<b>Admin-only-hint</b>: LiquiBase changelog file not found</br>" . "<pre>" . $e->getMessage() . "</pre>",
                        ]);              
                    }
                };
            }
        
        }        
    }

    /**
     * Inform the non-guest user, that the demo data will be resetted periodically.
     * This is a hint for the demo environment.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function DemoDataResetNote()
    {
        if (!Yii::$app->user->isGuest)
        {
            if (stristr(Yii::$app->urlManager->createAbsoluteUrl(['/']), 'demo'))
            {
                echo Alert::widget([
                        'options' => [
                                        'class' => 'alert-info',
                        ],
                        'body' => 'The demo data will be reset every 2 hours',
                ]);
            }
        }        
    }

    /**
     * Inform all user about how to login.
     * This is a hint for the demo environment.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function DemoLoginNotes()
    {
        if (Yii::$app->user->isGuest)
        {
            if (stristr(Yii::$app->urlManager->createAbsoluteUrl(['/']), 'demo'))
            {
                echo Alert::widget([
                        'options' => [
                                        'class' => 'alert-info',
                        ],
                        'body' => 'Login as <u><i>admin</i></u> with<br>&emsp;username:<b>admin</b><br>&emsp;password: <b>admin</b><br><br><br>or as <u><i>non-admin-user</i></u> with <br>&emsp;username: <b>user</b><br>&emsp;password: <b>user</b>',
                ]);
            }
        }
    }

    /**
     * Inform non-guest user, that a configuration update is needed in cases of using SQLite databases.
     * This is a hint for administrative users.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function SQLiteConnectionParameterUpdate()
    {
        if (!Yii::$app->user->isGuest)
        {
            if (Yii::$app->db->getDriverName() == "sqlite")
            {
                $configfile = Yii::$app->getBasePath()."/config/db.php";
                if (file_exists($configfile))
                {
                    $file_content = file_get_contents($configfile);
                    $stringToCheck = '$event->sender->createCommand("PRAGMA foreign_keys = ON")->execute()';
                    if( strpos($file_content,$stringToCheck) <= 0) {
                        
                        $helptextid=1000;
                        $url= Yii::$app->urlManager->createUrl(['shorthelp/index','helptextid'=>$helptextid]);
                        
                        $hlp_btn='<a class="btn btn-primary" href="' . $url . '">Help</a>';
                        echo Alert::widget([
                            'options' => [
                                    'class' => 'alert-danger',
                            ],
                            'body' => "<b>Important</b>: Please update your database connection!</br>" . "Goto $hlp_btn to see detailed information.",
                        ]);   
                    }
                }   
            }
        }
    }

    /**
     * Inform non-guest user, that the database is in read-only mode.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function DatabaseIsReadOnly()
    {
        if (!Yii::$app->user->isGuest)
        {
            $db_path = str_replace("sqlite:", "", Yii::$app->db->dsn);
            $Utils = new \vendor\meta_grid\helper\Utils();
            if ($Utils->DBisReadOnly(Yii::$app->db->getDriverName(), $db_path))
            {
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-warning',
                    ],
                    'body' =>Yii::t('app','The database is read-only. Try to insert or update objects will cause to an error message'),
                    ]);
            }
        }
    }    
    
    /**
     * Inform non-guest user, that a configuration update is needed.
     * This is a hint for administrative users.
     * Will echo a Bootstrap-Alert.
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 3.0
     * 
     */
    public function ConfigUpdateNeeded()
    {
        if (!Yii::$app->user->isGuest)
        {
            $configfile = Yii::$app->getBasePath()."/config/web.php";
            if (file_exists($configfile))
            {
                $file_content = file_get_contents($configfile);
                $file_content = trim(preg_replace('!\s+!', ' ', $file_content)); // ignore tab intend
        
                $stringToCheck = <<<CONFIGWEGITEM
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
CONFIGWEGITEM;
        
                $stringToCheck = trim(preg_replace('!\s+!', ' ', $stringToCheck)); // ignore tab intend
        
                if( strpos($file_content,$stringToCheck) <= 0) 
                {
                    $helptextid=1001;
                    $url= Yii::$app->urlManager->createUrl(['shorthelp/index','helptextid'=>$helptextid]);
                    
                    $hlp_btn='<a class="btn btn-primary" href="' . $url . '">Help</a>';
                    echo Alert::widget([
                        'options' => [
                                'class' => 'alert-danger',
                        ],
                        'body' => "<b>Important</b>: Please update your config file!</br>" . "Goto $hlp_btn to see detailed information.",
                    ]);   
                }
            }
        }
    }
}