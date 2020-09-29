<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\ExportFileDbTableFieldResult;

/**
 * ExportFileDbTableFieldResultSearch represents the model behind the search form about `VExportFileDbTableFieldResultSearchinterface`.
 */
class ExportFileDbTableFieldResultSearch extends ExportFileDbTableFieldResult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_client_id', 'fk_project_id', 'fk_db_table_id', 'fk_deleted_status_id', '_auto_id'], 'integer'],
            [['uuid', 'project_name', 'client_name', 'name', 'description', 'datatype', 'bulk_load_checksum', 'databaseInfoFromLocation', 'db_table_name', 'deleted_status_name', 'comments', 'mappings', 'session', '_created_datetime'], 'safe'],
            [['is_PrimaryKey', 'is_BusinessKey', 'is_GDPR_relevant'], 'boolean'],
            [['fk_client_id'], 'integer'],
        ];
    }

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
        $query = ExportFileDbTableFieldResult::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			        'pagination' => [
						'pageSize' => 100,
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
            'fk_object_type_id' => $this->fk_object_type_id,
            'fk_client_id' => $this->fk_client_id,
            'fk_project_id' => $this->fk_project_id,
            'fk_client_id' => $this->fk_client_id,
            'fk_db_table_id' => $this->fk_db_table_id,
            'is_PrimaryKey' => $this->is_PrimaryKey,
            'is_BusinessKey' => $this->is_BusinessKey,
            'is_GDPR_relevant' => $this->is_GDPR_relevant,
            'fk_deleted_status_id' => $this->fk_deleted_status_id,
            '_auto_id' => $this->_auto_id,
            '_created_datetime' => $this->_created_datetime,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'datatype', $this->datatype])
            ->andFilterWhere(['like', 'bulk_load_checksum', $this->bulk_load_checksum])
            ->andFilterWhere(['like', 'databaseInfoFromLocation', $this->databaseInfoFromLocation])
            ->andFilterWhere(['like', 'db_table_name', $this->db_table_name])
            ->andFilterWhere(['like', 'deleted_status_name', $this->deleted_status_name])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'mappings', $this->mappings])
            ->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
