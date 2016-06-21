<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Objectcomment */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Objectcomment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Objectcomments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objectcomment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
