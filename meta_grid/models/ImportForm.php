<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImportForm extends \yii\base\Model
{
    public $pastedValues;
    public $fk_object_type_id;
    public $fk_project_id;
    public $seperator;
    public $import_template_id;
    public $replace_string_to_null;

    public function rules()
    {
        return [
            [['fk_object_type_id', 'fk_project_id', 'import_template_id'], 'integer'],
            [['pastedValues'], 'string', 'max' => 5000000],
            [['replace_string_to_null'], 'string', 'max' => 100],
            [['seperator'], 'string', 'max' => 20],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['fk_project_id' => 'id']],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['fk_object_type_id' => 'id']],
        ];
    }
}