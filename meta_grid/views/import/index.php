<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;

/* @var $this yii\web\View */
/* @var $model app\models\ImportForm */
/* @var $form ActiveForm */
?>
<h2>The import via copy & paste is a <span style="color: red">beta</span> feature!</h2>

<?php
    	$Utils = new \vendor\meta_grid\helper\Utils();
        $beta_Features_enabled = $Utils->get_app_config("enable_beta_features");

        if ($beta_Features_enabled !== 1)
        {
            echo yii\bootstrap\Alert::widget([
                'options' => [
                        'class' => 'alert-warning',
                ],
                'body' => Yii::t('app','You are trying to open a beta feature. Beta features are currently not accessable. Please ask your administrator to enable them in the config.'),
            ]);
            return;
        }
?>

<div class="import-index">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'fk_object_type_id') ?>
        <?php 
            echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
        ?>
        <?php
            echo $form->field($model, 'import_template_id')->dropDownList($templateList, ['id'=>'name']);
        ?>
        <?= $form->field($model, 'seperator') ?>
        <?= $form->field($model, 'replace_string_to_null') ?>
        <?php
            // echo $form->field($model, 'pastedValues')->textarea();

            echo $form->field($model, 'pastedValues')->widget(
                CodemirrorWidget::className(),
                [
                    'assets'=>
                        [
                            CodemirrorAsset::THEME_BASE16_LIGHT,
                            CODEMIRRORASSET::ADDON_EDIT_MATCHBRACKETS,
                            CODEMIRRORASSET::ADDON_SELECTION_ACTIVE_LINE,
                            CODEMIRRORASSET::MODE_SPREADSHEET,
                        ],
                    'settings'=>
                        [
                            'lineNumbers' => true,
                            'mode' => 'text/x-mssql',
                            'theme' => 'base16-light',
                            'styleActiveLine' => true,
                            'matchBrackets' => true,
                        ]
                    ]
            );

        ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- import-index -->
