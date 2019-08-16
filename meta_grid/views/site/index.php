<style>
	a {
      position: relative;
    }
    
    a[data-tooltip]:before {
      position: absolute;
      left: 0;
      top: -40px;
      background-color: #c0c0c0;
      color: #000000;
      height: 30px;
      line-height: 30px;
      border-radius: 5px;
      padding: 0 15px;
      content: attr(data-tooltip);
      /* white-space: nowrap; */
      display: none;
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

<?php
use lajax\translatemanager\helpers\Language as Lx;
use yii\helpers\VarDumper;
/* @var $this yii\web\View */
$this->title = 'Meta#Grid'.(stristr(Yii::$app->homeUrl, 'dev') ? ' DEV' : '');

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
$alertBody .= $dbtablecontextCount==0 	? '<b>'.Yii::t('app','Table Context').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtablecontext') . '">here</a>.<br>' : "";
$alertBody .= $dbtabletypeCount==0 		? '<b>'.Yii::t('app','Table Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtabletype') . '">here</a>.<br>' : "";
$alertBody .= $toolCount==0 			? '<b>'.Yii::t('app','Tool').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tool') . '">here</a>.<br>' : "";
$alertBody .= $tooltypeCount==0 		? '<b>'.Yii::t('app','Tool Type').'</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tooltype') . '">here</a>.<br>' : "";
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
        case "contact-group":
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
                <?= UseMayAccessObject("contact-group") ?>
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
                <h2><?= printHeader("mapper;objectdependson;glossary;bracket", "Relations") ?></h2>
                <?= UseMayAccessObject("mapper") ?>
                <?= UseMayAccessObject("objectdependson") ?>
                <?= UseMayAccessObject("glossary") ?>
                <?= UseMayAccessObject("bracket") ?>
            </div>
         </div>

    </div>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (printHeader("client;project;keyfigure;attribute;contact;contact-group;sourcesystem;dbdatabase;dbtable;dbtablefield;dbtablecontext;dbtabletype;tool;tooltype;objectcomment;datatransferprocess;datatransfertype;datadeliveryobject;datadeliverytype;scheduling;mapper;objectdependson;glossary;bracket", "dummy") === "") {
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
