<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ToolType */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Tool Type')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tool Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tool-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
							]) ?>

</div>