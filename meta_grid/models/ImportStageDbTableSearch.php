<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ImportStageDbTableSearch represents the model behind the search form about `VImportStageDbTableSearchinterface`.
 */
// class ImportStageDbTableSearch extends VImportStageDbTableSearchinterface 
class ImportStageDbTableSearch extends \app\models\base\ImportStageDbTable 
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {      
        $query = \app\models\base\ImportStageDbTable::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			        'pagination' => [
						'pageSize' => 500,
					]
        ]);

		// this is the case, when the user makes his own filter criteria.
		if (array_key_exists(\yii\helpers\StringHelper::basename(get_class($this)),$params) === true)
		{
			$this->load($params);
		}
		else
		{
			$this->load(array_replace_recursive(\vendor\meta_grid\helper\PerspectiveHelper::SearchModelFilter($this), $params));
		}		
		
		// If select2-multiple option is true, the validation fails... 
        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        $query->andFilterWhere([
            'id' => $this->id,
            'isPrimaryKeyField' => $this->isPrimaryKeyField,
            'isForeignKeyField' => $this->isForeignKeyField,
            '_import_state' => $this->_import_state,
            'is_BusinessKey' => $this->is_BusinessKey,
            'is_GDPR_relevant' => $this->is_GDPR_relevant,
            'fk_project_id' => $this->fk_project_id,
            // 'fk_client_id' => $this->fk_client_id,
            'fk_db_database_id' => $this->fk_db_database_id,
            'column_cant_be_null' => $this->column_cant_be_null,
        ]);

        $query->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'db_table_name', $this->db_table_name])
            ->andFilterWhere(['like', 'db_table_description', $this->db_table_description])
            ->andFilterWhere(['like', 'db_table_field_name', $this->db_table_field_name])
            ->andFilterWhere(['like', 'db_table_field_datatype', $this->db_table_field_datatype])
            ->andFilterWhere(['like', 'db_table_field_description', $this->db_table_field_description])
            ->andFilterWhere(['like', 'db_table_type_name', $this->db_table_type_name])
            ->andFilterWhere(['like', 'db_table_context_name', $this->db_table_context_name])
            ->andFilterWhere(['like', 'db_table_context_prefix', $this->db_table_context_prefix])
            ->andFilterWhere(['like', 'foreignKey_table_name', $this->foreignKey_table_name])
            ->andFilterWhere(['like', 'foreignKey_table_field_name', $this->foreignKey_table_field_name])
            ->andFilterWhere(['like', '_import_date', $this->_import_date])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'database_or_catalog', $this->database_or_catalog])
            ->andFilterWhere(['like', 'schema', $this->schema])
            ->andFilterWhere(['like', 'column_default_value', $this->column_default_value])
            ->andFilterWhere(['like', 'additional_field_1', $this->additional_field_1])
            ->andFilterWhere(['like', 'additional_field_2', $this->additional_field_2])
            ->andFilterWhere(['like', 'additional_field_3', $this->additional_field_3])
            ->andFilterWhere(['like', 'additional_field_4', $this->additional_field_4])
            ->andFilterWhere(['like', 'additional_field_5', $this->additional_field_5])
            ->andFilterWhere(['like', 'additional_field_6', $this->additional_field_6])
            ->andFilterWhere(['like', 'additional_field_7', $this->additional_field_7])
            ->andFilterWhere(['like', 'additional_field_8', $this->additional_field_8])
            ->andFilterWhere(['like', 'additional_field_9', $this->additional_field_9]);

        return $dataProvider;
    }
}
