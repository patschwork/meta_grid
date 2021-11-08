<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "export_file_db_table_field_params".
 *
 * @property integer $id
 * @property string $session
 * @property integer $allowed_fk_project_id
 * @property integer $allowed_fk_client_id
 */
class ExportFileDbTableFieldParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_file_db_table_field_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session'], 'string'],
            [['allowed_fk_project_id', 'allowed_fk_client_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'session' => Yii::t('app', 'Session'),
            'allowed_fk_project_id' => Yii::t('app', 'Allowed Fk Project ID'),
            'allowed_fk_client_id' => Yii::t('app', 'Allowed Fk Client ID'),
        ];
    }
}
