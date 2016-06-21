<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tool */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Tool',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tools'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tool-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'tool_typeList' => $tool_typeList,		// autogeneriert ueber gii/CRUD
        
    ]) ?>

</div>
