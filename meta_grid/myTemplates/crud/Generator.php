<?php

namespace app\myTemplates\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;


/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
// class Generator extends \yii\gii\Generator
class Generator extends \yii\gii\generators\crud\Generator
{

// Following methods must stay in the class
	
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CRUD Generator meta#grid';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }


    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }
		
        $inConditions = [];
        $likeConditions = [];
        $hashConditions = [];
		$likeConditionConst = '';
        foreach ($columns as $column => $type) {
			$likeConditionConst = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
			// $likeConditionConst = "->andFilterWhere(['like', '{$column}', \$this->{$column}, false])";
			// $likeConditionConst = "->andFilterWhere(['like', '{$column}', '%'.\$this->{$column}.'%', false])";
			// $likeConditionConst = "->andFilterWhere(['like', '{$column}', '%' . is_null(\$this->{$column}) ? \"\" : \$this->{$column} .'%', false])";
			if (($this->modelClass === 'app\models\Bracket') && ($column === 'uuid'))
			{
				$likeConditionConst = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
			}
            switch ($type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                // case ( stristr($column, 'fk_').stristr($column, '_id') !== "" ):
                    // $inConditions[] = "->andFilterWhere(['in', '{$column}', \$this->{$column}])";
                    // break;
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    // $likeConditions[] = "->andFilterWhere(['like', '{$column}', '%'.\$this->{$column}.'%', false])";
                    $likeConditions[] = $likeConditionConst;
                    break;
            }
        }

        $conditions = [];
        if (!empty($inConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $inConditions) . ";\n";
        }
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }


}
