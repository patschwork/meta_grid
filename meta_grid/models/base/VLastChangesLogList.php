<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "v_LastChangesLog_List".
 *
 * @property integer $id
 * @property string $log_datetime
 * @property string $log_action
 * @property string $name
 * @property string $tablename
 */
class VLastChangesLogList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_LastChangesLog_List';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['log_datetime'], 'safe'],
            [['log_action', 'name', 'tablename'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'log_datetime' => Yii::t('app', 'Log Datetime'),
            'log_action' => Yii::t('app', 'Log Action'),
            'name' => Yii::t('app', 'Name'),
            'tablename' => Yii::t('app', 'Tablename'),
        ];
    }
}
