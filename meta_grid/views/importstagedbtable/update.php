<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\ImportStageDbTable */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Import Stage Db Table')]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Import Stage Db Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="import-stage-db-table-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'db_databaseList' => $db_databaseList,		// autogeneriert ueber gii/CRUD
        
				    ]) ?>

</div>