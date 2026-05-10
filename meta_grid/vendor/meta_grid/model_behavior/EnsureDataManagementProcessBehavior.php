<?php
namespace vendor\meta_grid\model_behavior;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

// T758

class EnsureDataManagementProcessBehavior extends Behavior
{
    public string $attribute = 'fk_datamanagement_process_id';
    public string $processName = 'Yii2 GUI';
    public string $processTable = '{{%datamanagement_process}}';

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'ensureProcess',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'ensureProcess',
        ];
    }

    public function ensureProcess(): void
    {
        $owner = $this->owner;
        // nur handeln, wenn Attribut leer oder auf einen ungültigen Wert gesetzt ist
        $val = $owner->{$this->attribute} ?? null;
        // if (!empty($val) && is_numeric($val)) {
        //     return;
        // }
        if ($owner->{$this->attribute} !== null && $owner->{$this->attribute} !== '') {
            return;
        }

        $db = Yii::$app->db;
        // 1) Try to find existing id by name
        $id = $db->createCommand('SELECT id FROM ' . $this->processTable . ' WHERE name = :name LIMIT 1', [
            ':name' => $this->processName,
        ])->queryScalar();

        if ($id === false) {
            // 2) Not found: insert and get id (use transaction to avoid race)
            $transaction = $db->beginTransaction();
            try {
                // Double-check inside transaction in case of race
                $id = $db->createCommand('SELECT id FROM ' . $this->processTable . ' WHERE name = :name LIMIT 1', [
                    ':name' => $this->processName,
                ])->queryScalar();

                if ($id === false) {
                    $db->createCommand()->insert($this->processTable, [
                        'name' => $this->processName,
                    ])->execute();
                    $id = $db->getLastInsertID();
                }

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        $owner->{$this->attribute} = (int)$id;
    }
}
