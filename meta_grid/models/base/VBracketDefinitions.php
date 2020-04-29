<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "v_Bracket_Definitions".
 *
 * @property string $bracket_searchPattern
 * @property string $bracket_name
 * @property string $bracket_description
 * @property integer $bracket_fk_attribute_id
 * @property integer $fk_object_type_id_as_searchFilter
 * @property integer $bracket_fk_project_id
 * @property integer $fk_bracket_id
 * @property string $db_table_name
 * @property string $db_table_field_name
 * @property integer $db_table_field_id
 * @property integer $db_table_field_fk_object_type_id
 * @property string $attribute_name
 * @property integer $attribute_id
 * @property integer $attribute_fk_object_type_id
 * @property integer $db_table_id
 */
class VBracketDefinitions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_Bracket_Definitions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bracket_fk_attribute_id', 'fk_object_type_id_as_searchFilter', 'bracket_fk_project_id', 'fk_bracket_id', 'db_table_field_id', 'db_table_field_fk_object_type_id', 'attribute_id', 'attribute_fk_object_type_id', 'db_table_id'], 'integer'],
            [['bracket_searchPattern'], 'string', 'max' => 500],
            [['bracket_name', 'db_table_name', 'db_table_field_name', 'attribute_name'], 'string', 'max' => 250],
            [['bracket_description'], 'string', 'max' => 4000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bracket_searchPattern' => Yii::t('app', 'Bracket Search Pattern'),
            'bracket_name' => Yii::t('app', 'Bracket Name'),
            'bracket_description' => Yii::t('app', 'Bracket Description'),
            'bracket_fk_attribute_id' => Yii::t('app', 'Bracket Fk Attribute ID'),
            'fk_object_type_id_as_searchFilter' => Yii::t('app', 'Fk Object Type Id As Search Filter'),
            'bracket_fk_project_id' => Yii::t('app', 'Bracket Fk Project ID'),
            'fk_bracket_id' => Yii::t('app', 'Fk Bracket ID'),
            'db_table_name' => Yii::t('app', 'Db Table Name'),
            'db_table_field_name' => Yii::t('app', 'Db Table Field Name'),
            'db_table_field_id' => Yii::t('app', 'Db Table Field ID'),
            'db_table_field_fk_object_type_id' => Yii::t('app', 'Db Table Field Fk Object Type ID'),
            'attribute_name' => Yii::t('app', 'Attribute Name'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'attribute_fk_object_type_id' => Yii::t('app', 'Attribute Fk Object Type ID'),
            'db_table_id' => Yii::t('app', 'Db Table ID'),
        ];
    }
}
