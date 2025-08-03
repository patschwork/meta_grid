<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\VarDumper as VarDumperVarDumper;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$add_fk_client_id = false;
if (strstr(implode(" ", $rules), 'fk_project_id'))
{
    $add_fk_client_id = true;
    $searchFor = '\'fk_project_id\' => $this->fk_project_id,';
    $replaceWith = $searchFor . "\n" . str_repeat(' ', 12) . '\'fk_client_id\' => $this->fk_client_id,';
    $searchConditions[0] = str_replace($searchFor, $replaceWith, $searchConditions[0]);
}

$add_databaseInfoFromLocation = false;
if ($modelClass === "DbTable" || $modelClass === "DbTableField")
{
    $add_databaseInfoFromLocation = true;
    $searchFor = '$query->andFilterWhere([\'like\', \'uuid\', $this->uuid])';
    $replaceWith = $searchFor . "\n" . str_repeat(' ', 12) . '->andFilterWhere([\'like\', \'databaseInfoFromLocation\', $this->databaseInfoFromLocation])';
    $replaceWith .= "\n" . str_repeat(' ', 12) . '->andFilterWhere([\'like\', \'schemaInfoFromLocation\', $this->schemaInfoFromLocation])';
    $searchConditions[1] = str_replace($searchFor, $replaceWith, $searchConditions[1]);
}

echo "<?php\n";
$searchInterfaceModelClassName = "V" . StringHelper::basename($generator->searchModelClass) . "interface";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
<?php // echo "use ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? \" as $modelAlias\" : \"\"); ?>
use <?= "app\\models\\" . ltrim($searchInterfaceModelClassName, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $searchInterfaceModelClassName ?>`.
 */
<?php // echo "class $searchModelClass extends " . isset($modelAlias) ? $modelAlias : $modelClass; ?>
class <?= $searchModelClass ?> extends <?= $searchInterfaceModelClassName ?> 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) . ($add_fk_client_id ? ",\n". str_repeat(' ', 12) . "[['fk_client_id'], 'integer']" : '') . ($add_databaseInfoFromLocation ? ",\n". str_repeat(' ', 12) . "[['databaseInfoFromLocation','schemaInfoFromLocation'], 'safe']" : '') ?>,
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
        <?php // echo "\$query = " . isset($modelAlias) ? $modelAlias : $modelClass . "::find();"; ?>
<?php echo "\$query = " . $searchInterfaceModelClassName . "::find();"; ?>
        
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

        <?= implode("\n        ", $searchConditions) ?>

        // {... T363
        $db = Yii::$app->db;
        $db->cache(function () use ($dataProvider) {
            $dataProvider->prepare();
        });
        // T363 ...}

        return $dataProvider;
    }
}
