<style>
	a {
      position: relative;
    }
    
    a[data-tooltip]:before {
        position: absolute;
        left: 0;
        /* top: -40px; */
        bottom: 40px; /* is needed for text with line-break*/
        background-color: #c0c0c0;
        color: #000000;
        /* height: 30px; */
        line-height: 30px;
        border-radius: 5px;
        padding: 0 15px;
        content: attr(data-tooltip);
        /* white-space: nowrap; */
        white-space: pre; 
        display: none;
        text-align: left;
      }
  
    
    a[data-tooltip]:after {
      position: absolute;
      left: 15px;
      top: -10px;
      border-top: 7px solid #c0c0c0;
      border-left: 7px solid transparent;
      border-right: 7px solid transparent;
      content: "";
      display: none;
    }
    
    a[data-tooltip]:hover:after, a[data-tooltip]:hover:before {
      display: block;
	  z-index: 10;
    }


</style>

<script>
function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}
function getCookie(c_name) {
    var i, x, y, thisCookies = document.cookie.split(";");
    for (i = 0; i < thisCookies.length; i++) {
        x = thisCookies[i].substr(0, thisCookies[i].indexOf("="));
        y = thisCookies[i].substr(thisCookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
}
function hideMeSomeDays()
{
    setCookie('hideNewVersionForDays', '3', 3);
    var htmlElement = document.getElementsByName("new-version-info-start")[0];
    htmlElement.hidden = true;

}
</script>

<?php
use lajax\translatemanager\helpers\Language as Lx;

/* @var $this yii\web\View */
$this->title = 'Meta#Grid'.(stristr(Yii::$app->homeUrl, 'dev') ? ' DEV' : '');

if (!Yii::$app->user->isGuest)
{
    $newVersionAvaiable = \vendor\meta_grid\helper\ApplicationVersion::isNewApplicationAvailable();
    
    if ($newVersionAvaiable)
    {
        $msg = Yii::t('', 'A new Version {newVersion} of {appName} is available. Your current version is {currentVersion}.<br><br>Read the <a class="btn btn-primary" href="{linkToDoc}" target="_blank">documentation</a> how to update the application.<br><br><button class="btn btn-warning" onclick="hideMeSomeDays()">Hide this message for 3 days</button>'
                ,['newVersion' => \vendor\meta_grid\helper\ApplicationVersion::getCookieNewerVersion(), 'currentVersion' => \vendor\meta_grid\helper\ApplicationVersion::$applicationVersion, 'linkToDoc' => 'https://blog.meta-grid.com/category/docs/update', 'appName' => \vendor\meta_grid\helper\ApplicationVersion::getApplicationName()]);
        echo yii\bootstrap\Alert::widget([
            'options' => [
                    'class' => 'alert-info',
                    'name' => 'new-version-info-start',
            ],
            'body' => $msg,
        ]);
    }
}


$wa_gui_url = Yii::$app->urlManager->baseUrl . "/../../../../../dwh_meta_v2_wa_gui/";

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
	echo yii\bootstrap\Alert::widget([
			'options' => [
					'class' => 'alert-warning',
			],
			'body' => Yii::t('app','Following objecttypes does not have any items. They are basically needed for further working. You should start here.').$alertBody,
	]);
}

try
{
    // get the path from the app_config database table. If not exists, then use a default.
    $app_config_liquibase_changelog_master_filepath = \vendor\meta_grid\helper\Utils::get_app_config("liquibase_changelog_master_filepath");

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
        echo yii\bootstrap\Alert::widget([
            'options' => [
                    'class' => 'alert-warning',
            ],
            'body' => $alertBody2,
        ]);
    }
}
catch (Exception $e) {
    // Ignore any errors...

    // if admin user then show a hint:
    if (isset(Yii::$app->user->identity->isAdmin))
    {
        if (Yii::$app->user->identity->isAdmin) 
        {
            if ($app_config_liquibase_changelog_master_filepath != "")
            {
                echo yii\bootstrap\Alert::widget([
                    'options' => [
                            'class' => 'alert-danger',
                    ],
                    'body' => "<b>Admin-only-hint</b>: LiquiBase changelog file not found</br>" . "<pre>" . $e->getMessage() . "</pre>",
                ]);              
            }
        };
    }

}

if (!Yii::$app->user->isGuest)
{
    if (stristr(Yii::$app->urlManager->createAbsoluteUrl(['/']), 'demo'))
    {
        echo yii\bootstrap\Alert::widget([
                'options' => [
                                'class' => 'alert-info',
                ],
                'body' => 'The demo data will be reset every 2 hours',
        ]);
    }
}

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
                echo yii\bootstrap\Alert::widget([
                    'options' => [
                            'class' => 'alert-danger',
                    ],
                    'body' => "<b>Important</b>: Please update your database connection!</br>" . "Goto $hlp_btn to see detailed information.",
                ]);   
            }
        }   
    }

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
            echo yii\bootstrap\Alert::widget([
                'options' => [
                        'class' => 'alert-danger',
                ],
                'body' => "<b>Important</b>: Please update your config file!</br>" . "Goto $hlp_btn to see detailed information.",
            ]);   
        }
    }
}

function UseMayAccessObject($objecttype) {
    $tooltip      = "";
    $buttonTitle  = "";
    $defaultcolor = "black";
    $borderstyle  = "";
    $itemTemplate = "<p><a style=\"width: 250px; color: {{{defaultcolor}}}; {{{borderstyle}}}\" class=\"btn btn-default\" data-tooltip=\"{{{tooltip}}}\" href=\"{{{href}}}\">{{{buttontitle}}} &raquo;</a></p>";
    $item         = "";
    $urlHref      = "";
    $urlHref      = Yii::$app->urlManager->createUrl($objecttype);
 
    switch ($objecttype) {
        case "client":
            // $tooltip     = "Kunden-/Mandanteneinstellung";
            $buttonTitle = "Client";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "project":
            // $tooltip     = "Projekte";
            $buttonTitle = "Project";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "keyfigure":
            // $tooltip     = "Kennzahlen und KPIs";
            $buttonTitle = "Keyfigure";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "attribute":
            // $tooltip     = "Attribute und Kennzeichen";
            $buttonTitle = "Attribute";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "contact":
            // $tooltip     = "Kontaktinformationen und Ansprechpartner";
            $buttonTitle = "Contact";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "contactgroup":
            // $tooltip     = "Kontaktgruppen in welchen Kontakte wirken (Abteilung, Team, Projekt, etc.)";
            $buttonTitle = "ContactGroup";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "sourcesystem":
            // $tooltip     = "Quellsysteme";
            $buttonTitle = "Sourcesystem";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "dbdatabase":
            // $tooltip     = "Datenbanken, Universum, Cube, ...";
            $buttonTitle = "Database";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "dbtable":
            // $tooltip     = "Datenbanktabellen, -Views, Exceltabelle, CSV-Dateien, ...";
            $buttonTitle = "Table";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "dbtablefield":
            // $tooltip     = "Felder einer dbtable";
            $buttonTitle = "Table Field";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "dbtablecontext":
            // $tooltip     = "Kontext einer dbtable, z.B. Staging-, Fakt-, Dimension-Tabelle, ...";
            $buttonTitle = "Table Context";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "dbtabletype":
            // $tooltip     = "Tabellentyp, z.B. Datenbanktabelle/-view, CSV-Datei, ...";
            $buttonTitle = "Table Type";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "tool":
            $buttonTitle = "Tool";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "tooltype":
            // $tooltip     = "Art der Programmsuite, z.B. Datenbanksystem, Officeprogramm, BI-Suite, ...";
            // $tooltip     = "Type of programms uite, e.g. RDBMS, office suite, BI suite, ...";
            $buttonTitle = "Tool Type";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "objectcomment":
            // $tooltip     = "Alle Kommentare zu Objekten";
            // $tooltip     = "All comments of objects";
            $buttonTitle = "Comments";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "datatransferprocess":
            // $tooltip     = "ETL, StoredProcedure zur Datentransformation etc.";
            // $tooltip     = "ETL, StoredProcedure for data transformation etc.";
            $buttonTitle = "Data Transfer Process";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "datatransfertype":
            // $tooltip     = "Datentransfertyp";
            // $tooltip     = "Definition of data transferring";
            $buttonTitle = "Data Transfer Type";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "datadeliveryobject":
            // $tooltip     = "Report, Exportschnittstelle, Exportdatei, ...";
            // $tooltip     = "Reports, export interface, export file, ...";
            $buttonTitle = "Data Delivery Object";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "datadeliverytype":
            // $tooltip     = "Welcher Art der Belieferung";
            // $tooltip     = "Definition of delivery";
            $buttonTitle = "Data Delivery Type";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "scheduling":
            // $tooltip     = "Planung zur automatischen, regelm&auml;&szlig;igen Prozessen";
            // $tooltip     = "Scheduling of recurring processes";
            $buttonTitle = "Scheduling";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "mapper":
            // $tooltip     = "Verknuepfungen zu Objekten";
            // $tooltip     = "Link to objects to objects";
            $buttonTitle = "Mappings";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "objectdependson":
            // $tooltip     = "Abhaengigkeiten";
            // $tooltip     = "Dependencies";
            $buttonTitle = "Object depends on";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "glossary":
            // $tooltip     = "Glossar";
            $buttonTitle = "Glossary";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "bracket":
            // $tooltip     = "Zusammenfassen von Objekten";
            // $tooltip     = "Grouping of objects";
            $buttonTitle = "Bracket";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        case "url":
            // $tooltip     = "Zusammenfassen von Objekten";
            // $tooltip     = "Grouping of objects";
            $buttonTitle = "URL";
            $tooltip     = "TooltipMainPage: " . $buttonTitle;
            break;
        }
    $item = $itemTemplate;
    $item = str_replace("{{{tooltip}}}", Yii::t('app', $tooltip), $item);
    $item = str_replace("{{{href}}}", $urlHref, $item);
    $item = str_replace("{{{buttontitle}}}", Yii::t('app', $buttonTitle), $item);
	
    $borderstyle  = "border: #0094FF solid 1px;";
	if (\vendor\meta_grid\helper\PerspectiveHelper::getHintForHighlightMainMenu('app', $buttonTitle) === false)
    {
    	$defaultcolor = "silver";
    	$borderstyle  = "border: silver dashed 1px;";
    }
	else
	{
		if (\vendor\meta_grid\helper\PerspectiveHelper::getInfoIfMasterLang() === false)
		{
			$borderstyle  = "border-color: #FFDD38; box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 221, 56, 0.6);";			
		}
	}
	
	// Highlight non translated buttons
	if (Yii::$app->language !== "en-US")
	{
		// if (Yii::t('app', $buttonTitle) === $buttonTitle)
		if (!\vendor\meta_grid\helper\PerspectiveHelper::translationExists('app', $buttonTitle))
		{
			$borderstyle  = "border: red solid 1px;";	
		}		
	}
	
    $item = str_replace("{{{defaultcolor}}}", $defaultcolor, $item);
    $item = str_replace("{{{borderstyle}}}", $borderstyle, $item);
    
    // echo "INSERT INTO language_source (category, message) VALUES ('app','$tooltip');\n";
    // echo "INSERT INTO language_source (category, message) VALUES ('app','$buttonTitle');\n";
	// return "";
	
    $isAdmin = FALSE;
    if (isset(Yii::$app->user->identity->isAdmin))
    {
        if (Yii::$app->user->identity->isAdmin) $isAdmin = TRUE;
    }

    if ((Yii::$app->User->can('view-' . $objecttype)) || ($isAdmin)) {
        return $item . "\n";
    }
    else {
        return "";
    }    
}

function printHeader($csvObjecttypes, $title) {
    if (isset(Yii::$app->user->identity->isAdmin))
    {
        if (Yii::$app->user->identity->isAdmin) return Yii::t('general', $title);
    }    

    $splitRoleNames = explode(";", $csvObjecttypes);

    foreach($splitRoleNames as $role) {
        if (Yii::$app->User->can('view-' . $role)) {
            return Yii::t('general', $title);
        }        
    }
    return "";
}
?>




<div class="site-index">

<?php if (Yii::$app->user->isGuest): ?>

     <div class="jumbotron">
        <h1>Welcome to Meta#Grid</h1>
     
        <p class="lead"><?php echo Lx::t('general','Log in, to spin up your metadata'); ?></p>
		
        <!--  <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p> -->
     </div>
        <!--  <p><a class="btn btn-lg btn-primary" href="<?= $wa_gui_url ?>sourcesystem.php">Workaround GUI</a></p>  -->
<?php endif; ?>        
        
        <!--  <a style="width: 100%;" class="btn btn-primary" href="<?= Yii::$app->urlManager->createUrl(['mapobject2object/appglobalsearch','q'=>""]) ?>">Search</a>  -->
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2><?= printHeader("client;project", "Base Settings") ?></h2>
                <?= UseMayAccessObject("client") ?>
                <?= UseMayAccessObject("project") ?>
                <h2><?= printHeader("keyfigure;attribute", "Business Rules") ?></h2>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" href="<?= $wa_gui_url ?>keyfigure.php">Keyfigure &raquo;</a></p> -->
                <?= UseMayAccessObject("keyfigure") ?>
                <?= UseMayAccessObject("attribute") ?>
                <!--  <p><a style="width: 250px;" class="btn btn-danger" href="<?= $wa_gui_url ?>attribute.php">Attribute &raquo;</a></p> -->
                <h2><?= printHeader("contact;contact-group", "Organisation") ?></h2>
                <?= UseMayAccessObject("contact") ?>
                <?= UseMayAccessObject("contactgroup") ?>
            </div>
            <div class="col-lg-4">
                <h2><?= printHeader("sourcesystem;dbdatabase;dbtable;dbtablefield;dbtablecontext;dbtabletype;tool;tooltype;objectcomment", "Technical Information") ?></h2>
				<?= UseMayAccessObject("sourcesystem") ?>
				<?= UseMayAccessObject("dbdatabase") ?>
				<?= UseMayAccessObject("dbtable") ?>
				<?= UseMayAccessObject("dbtablefield") ?>
				<?= UseMayAccessObject("dbtablecontext") ?>
				<?= UseMayAccessObject("dbtabletype") ?>
				<?= UseMayAccessObject("tool") ?>
				<?= UseMayAccessObject("tooltype") ?>
                <?php if (
                    (printHeader("sourcesystem;dbdatabase;dbtable;dbtablefield;dbtablecontext;dbtabletype;tool;tooltype", "test1")==="test1") 
                    && (printHeader("objectcomment","test2")==="test2"))
                    {
                        echo "<hr>";
                    } ?>
				<?= UseMayAccessObject("objectcomment") ?>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_database.php">Database &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table.php">Table &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_field.php">Table Field &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_context.php">Table Context &raquo;</a></p> -->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_type.php">Table Type &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>tool.php">Tool &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>tool_type.php">Tool Type &raquo;</a></p>	-->
               
            </div>
            <div class="col-lg-4">
                <h2><?= printHeader("datatransferprocess;datatransfertype;datadeliveryobject;datadeliverytype;scheduling", "Presentation and Data Movement") ?></h2>
                <?= UseMayAccessObject("datatransferprocess") ?>
                <?= UseMayAccessObject("datatransfertype") ?>
                <?php if (
                    (printHeader("datatransferprocess;datatransfertype", "test1")==="test1") 
                    && (printHeader("datadeliveryobject;datadeliverytype","test2")==="test2"))
                    {
                        echo "<hr>";
                    } ?>
                <?= UseMayAccessObject("datadeliveryobject") ?>
                <?= UseMayAccessObject("datadeliverytype") ?>
                <?php if (
                    (printHeader("datatransferprocess;datatransfertype;datadeliveryobject;datadeliverytype", "test1")==="test1") 
                    && (printHeader("scheduling","test2")==="test2"))
                    {
                        echo "<hr>";
                    } ?>
                <?= UseMayAccessObject("scheduling") ?>                
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_transfer_process.php">Data Transfer Process &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_transfer_type.php">Data Transfer Type &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('datatransfertype') ?>">Test &raquo;</a></p> -->
                <!--	<p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_delivery_object.php">Data Delivery Object &raquo;</a></p>	-->
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_delivery_type.php">Data Delivery Type &raquo;</a></p>	-->
            </div>
            <div class="col-lg-4">
                <h2><?= printHeader("mapper;objectdependson;glossary;bracket;url", "Relations") ?></h2>
                <?= UseMayAccessObject("mapper") ?>
                <?= UseMayAccessObject("objectdependson") ?>
                <?= UseMayAccessObject("glossary") ?>
                <?= UseMayAccessObject("bracket") ?>
                <?= UseMayAccessObject("url") ?>
            </div>
         </div>

    </div>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (printHeader("client;project;keyfigure;attribute;contact;contact-group;sourcesystem;dbdatabase;dbtable;dbtablefield;dbtablecontext;dbtabletype;tool;tooltype;objectcomment;datatransferprocess;datatransfertype;datadeliveryobject;datadeliverytype;scheduling;mapper;objectdependson;glossary;bracket;url", "dummy") === "") {
            echo yii\bootstrap\Alert::widget([
                'options' => [
                        'class' => 'alert-warning',
                ],
                'body' => Yii::t('general','You have no permission to view any objecttypes.<br>Please contact your administrator for permission settings.'),
            ]);
        }
    }
    ?>
</div>

<?php 
	// $url_CTRL_SHIFT_F = Yii::$app->urlManager->createUrl(['mapper/appglobalsearch', 'q' => '']);
	// $this->registerJs("
    // shortcut.add(\"ctrl+shift+f\", function() {
		// location.href=\"$url_CTRL_SHIFT_F\";
    // }); 
	// ", $this::POS_END, 'shortcut'); 
?>
<?php 
	$this->registerJs("
    cookievalue=getCookie(\"hideNewVersionForDays\");
    if (cookievalue == 3)
    {
        var htmlElement = document.getElementsByName(\"new-version-info-start\")[0];
        htmlElement.hidden = true;
    }
    ; 
	", $this::POS_READY, 'hide-new-version-info'); 
?>
