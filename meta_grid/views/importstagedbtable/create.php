<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\ImportStageDbTable */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Import Stage Db Table')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Import Stage Db Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-stage-db-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'db_databaseList' => $db_databaseList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>