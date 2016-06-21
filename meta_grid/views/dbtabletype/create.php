<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DBTableType */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Dbtable Type',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dbtable Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dbtable-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		        
    ]) ?>

</div>
