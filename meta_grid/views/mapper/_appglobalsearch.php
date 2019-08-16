<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\VAllObjectsUnion;
use yii\helpers\Url;				// Patrick, 2016-01-17
use yii\helpers\Json;				// Patrick, 2016-01-17
use kartik\depdrop\DepDrop;			// Patrick, 2016-01-17
use kartik\select2\Select2;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 

	    $object_type_Model = new \app\models\ObjectType();
	    $model1 = new \app\models\VAllObjectsUnion();
	    $all_objects = $model1::find()->all();
	    $object_typeList = [];
	    foreach($all_objects as $object_item)
	    {
	    	$object_typeList[$object_item->object_type_name][$object_item->listkey]=$object_item->listvalue_1;
	    }
	    // Shortcuts in der Suche sollen eine Hilfestellung bieten, um den Einstieg leichter zu machen
		
	    // db_table
	    $object_typeList["Shortcut"]["Shortcut-1;4"]="Dimensionstabelle definieren? (Tabellenkontext 'Dimensionstabelle' bereits vorhanden)";
	    $object_typeList["Shortcut"]["Shortcut-2;4"]="SAP BusinessObject Universums Klasse (db_table) definieren?  (Tabellenkontext 'SAP BO Klasse' bereits vorhanden)";
	    $object_typeList["Shortcut"]["Shortcut-3;4"]="Stagingtabelle (db_table) definieren?  (Tabellenkontext 'Stagingtabelle' bereits vorhanden)";
	     
	    // db_table_context
	    $object_typeList["Shortcut"]["Shortcut-4;6"]="Dimensionstabellen als Tabellenkontext (db_table_context) anlegen?";
	    $object_typeList["Shortcut"]["Shortcut-5;6"]="SAP BusinessObject Universums Klasse als Tabellenkontext (db_table_context) anlegen?";
	     
	    
// 	    // Using a select2 widget inside a modal dialog
// 	    Modal::begin([
// 	    'options' => [
// 	    'id' => 'kartik-modal',
// 	    'tabindex' => false // important for Select2 to work properly
// 	    ],
// 	    'header' => '<h4 style="margin:0; padding:0">Select2 Inside Modal</h4>',
// 	    'toggleButton' => ['label' => 'Show Modal', 'class' => 'btn btn-lg btn-primary'],
// 	    ]);
	    
       	// Without model and implementing a multiple select
       	echo '<label class="control-label">Search for anything</label>';
       	echo Select2::widget([
       			'name' => 'openobject',
       			'data' => $object_typeList,
       			'options' => 
       			[
					'placeholder' => 'Search',
       			],
       	]);

//      echo "<br><br><br>";
//      echo '<div class="form-group">';
//      echo Html::submitButton(Yii::t('app', 'Goto'), ['class' => 'btn btn-primary']);
// 		echo "</div>";
       	       	
//        	Modal::end();
    ?>
	<br><br><br>
    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Goto'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
