<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$this->title = $modelDbTable->name;
//$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'DbTable')]) . ' ' . $modelDbTable->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Field Multiple'), 'url' => ['dbtable/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDbTable->name, 'url' => ['dbtable/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelDbTable' => $modelDbTable, 
    //    'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
    // 'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
    // 'attributeList' => $attributeList,		// autogeneriert ueber gii/CRUD
    // 'object_type_as_searchFilterList' => $object_type_as_searchFilterList,		// autogeneriert ueber gii/CRUD
        
					'modelsDbTableField' => isset($modelsDbTableField) ? $modelsDbTableField : null,

'projectList' => $projectList,
'db_table_contextList' => $db_table_contextList,
'db_table_typeList' => $db_table_typeList,
'deleted_statusList' => $deleted_statusList,

]) ?>

</div>