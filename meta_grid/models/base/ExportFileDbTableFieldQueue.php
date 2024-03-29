<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "export_file_db_table_field_queue".
 *
 * @property integer $id
 * @property string $session
 */
class ExportFileDbTableFieldQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_file_db_table_field_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session'], 'string'],
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
        ];
    }
}
