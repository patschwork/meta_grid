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
    }
</style>

<?php
/* @var $this yii\web\View */
$this->title = 'Meta#Grid';

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
$alertBody .= $dbtablecontextCount==0 	? '<b>Table Context</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtablecontext') . '">here</a>.<br>' : "";
$alertBody .= $dbtabletypeCount==0 		? '<b>Table Type</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('dbtabletype') . '">here</a>.<br>' : "";
$alertBody .= $toolCount==0 			? '<b>Tool</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tool') . '">here</a>.<br>' : "";
$alertBody .= $tooltypeCount==0 		? '<b>Tool Type</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('tooltype') . '">here</a>.<br>' : "";
$alertBody .= $datatransfertypeCount==0 ? '<b>Data Transfer Type</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('datatransfertype') . '">here</a>.<br>' : "";
$alertBody .= $datadeliverytypeCount==0 ? '<b>Data Delivery Type</b> is empty. Open <a href="' . Yii::$app->urlManager->createUrl('datadeliverytype') . '">here</a>.<br>' : "";

if ($foundIssues>0)
{ 
	echo yii\bootstrap\Alert::widget([
			'options' => [
					'class' => 'alert-warning',
			],
			'body' => 'Following objecttypes does not have any items. They are basically needed for further working. You should start here.'.$alertBody,
	]);
}
?>

<div class="site-index">

    <!-- <div class="jumbotron">
        <h1>Congratulations!</h1> -->

        <!--  <p class="lead">You have successfully created your Yii-powered application.</p> -->
		
        <!--  <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>	-->
        <!--  <p><a class="btn btn-lg btn-primary" href="<?= $wa_gui_url ?>sourcesystem.php">Workaround GUI</a></p>  -->
        
        
        <!--  <a style="width: 100%;" class="btn btn-primary" href="<?= Yii::$app->urlManager->createUrl(['mapobject2object/appglobalsearch','q'=>""]) ?>">Search</a>  -->
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Base Settings</h2>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Kunden-/Mandanteneinstellung" href="<?= Yii::$app->urlManager->createUrl('client') ?>">Client &raquo;</a></p>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Projekte" href="<?= Yii::$app->urlManager->createUrl('project') ?>">Project &raquo;</a></p>
                <h2>Business Rules</h2>
                <p><a style="width: 250px;" class="btn btn-warning" href="<?= $wa_gui_url ?>keyfigure.php">Keyfigure &raquo;</a></p>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Attribute und Kennzeichen" href="<?= Yii::$app->urlManager->createUrl('attribute') ?>">Attribute &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-danger" href="<?= $wa_gui_url ?>attribute.php">Attribute &raquo;</a></p> -->
            </div>
            <div class="col-lg-4">
                <h2>Technical Information</h2>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Quellsysteme" href="<?= Yii::$app->urlManager->createUrl('sourcesystem') ?>">Sourcesystem &raquo;</a></p>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Datenbanken, Universum, Cube, ..." href="<?= Yii::$app->urlManager->createUrl('dbdatabase') ?>">Database &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_database.php">Database &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Datenbanktabellen, -Views, Exceltabelle, CSV-Dateien, ..." href="<?= Yii::$app->urlManager->createUrl('dbtable') ?>">Table &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table.php">Table &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Felder einer dbtable" href="<?= Yii::$app->urlManager->createUrl('dbtablefield') ?>">Table Field &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_field.php">Table Field &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Kontext einer dbtable, z.B. Staging-, Fakt-, Dimension-Tabelle, ..." href="<?= Yii::$app->urlManager->createUrl('dbtablecontext') ?>">Table Context &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_context.php">Table Context &raquo;</a></p> -->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Tabellentyp, z.B. Datenbanktabelle/-view, CSV-Datei, ..." href="<?= Yii::$app->urlManager->createUrl('dbtabletype') ?>">Table Type &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>db_table_type.php">Table Type &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Programmsuite" href="<?= Yii::$app->urlManager->createUrl('tool') ?>">Tool &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>tool.php">Tool &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Art der Programmsuite, z.B. Datenbanksystem, Officeprogramm, BI-Suite, ..." href="<?= Yii::$app->urlManager->createUrl('tooltype') ?>">Tool Type &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>tool_type.php">Tool Type &raquo;</a></p>	-->
				<!--  <p><a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('tooltype') ?>">Tool type&raquo;</a></p>  -->
                
            </div>
            <div class="col-lg-4">
                <h2>Presentation and Data Movement</h2>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="ETL, StoredProcedure zur Datentransformation etc. " href="<?= Yii::$app->urlManager->createUrl('datatransferprocess') ?>">Data Transfer Process &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_transfer_process.php">Data Transfer Process &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Datentransfertyp" href="<?= Yii::$app->urlManager->createUrl('datatransfertype') ?>">Data Transfer Type &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_transfer_type.php">Data Transfer Type &raquo;</a></p>	-->
                <hr>
                <!--  <p><a style="width: 250px;" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('datatransfertype') ?>">Test &raquo;</a></p> -->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Report, Exportschnittstelle, Exportdatei, ..." href="<?= Yii::$app->urlManager->createUrl('datadeliveryobject') ?>">Data Delivery Object &raquo;</a></p>
                <!--	<p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_delivery_object.php">Data Delivery Object &raquo;</a></p>	-->
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Welcher Art der Belieferung" href="<?= Yii::$app->urlManager->createUrl('datadeliverytype') ?>">Data Delivery Type &raquo;</a></p>
                <!--  <p><a style="width: 250px;" class="btn btn-warning" target="_blank" href="<?= $wa_gui_url ?>data_delivery_type.php">Data Delivery Type &raquo;</a></p>	-->
                <hr>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Planung zur automatischen, regelm&auml;&szlig;igen Prozessen" href="<?= Yii::$app->urlManager->createUrl('scheduling') ?>">Scheduling &raquo;</a></p>

                                                
            </div>
            <div class="col-lg-4">
                <h2>Relations</h2>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Verknuepfungen zu Objekten" href="<?= Yii::$app->urlManager->createUrl('mapobject2object') ?>">Mappings &raquo;</a></p>
                <p><a style="width: 250px;" class="btn btn-default" data-tooltip="Glossar" href="<?= Yii::$app->urlManager->createUrl('glossary') ?>">Glossary &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Organisation</h2>
                <p><a style="width: 250px;" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('contact') ?>">Contact &raquo;</a></p>
                <p><a style="width: 250px;" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('contactgroup') ?>">ContactGroup &raquo;</a></p>
            </div>
         </div>

    </div>
</div>
