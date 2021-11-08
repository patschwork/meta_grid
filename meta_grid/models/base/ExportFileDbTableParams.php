<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "export_file_db_table_params".
 *
 * @property integer $id
 * @property string $session
 * @property integer $allowed_fk_project_id
 * @property integer $allowed_fk_client_id
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 */
class ExportFileDbTableParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_file_db_table_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session'], 'string'],
            [['allowed_fk_project_id', 'allowed_fk_client_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
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
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDatamanagementProcess()
    {
        return $this->hasOne(\app\models\DatamanagementProcess::className(), ['id' => 'fk_datamanagement_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectPersistenceMethod()
    {
        return $this->hasOne(\app\models\ObjectPersistenceMethod::className(), ['id' => 'fk_object_persistence_method_id']);
    }
}
