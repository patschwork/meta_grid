<?php
namespace vendor\meta_grid\helper;
use Yii;
use yii\helpers\VarDumper;

class PerspectiveHelper
{
	public static function getLanguageList()
	{
		$orderby = new \yii\db\Expression("CASE WHEN language_id='en-US' THEN 1 WHEN language_id=='de-DE' THEN 2 ELSE 99 END, name");
		$languages = \lajax\translatemanager\models\Language::find()->where(["status" => 1])->orderBy($orderby)->all(); //->all();
		
		return $languages;
	}

	public static function getInfoIfMasterLang() 
	{
		$languages = \lajax\translatemanager\models\Language::find()->where(['language_id' => Yii::$app->language])->one();
		if (is_null($languages)) return false;
		$language_name_ascii = $languages->name_ascii;
		if (explode("-",$language_name_ascii)[1] === "Master")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function getHintForHighlightMainMenu($category, $message)
	{
		$languageSources = new \lajax\translatemanager\models\LanguageSource();
		$resSource = $languageSources->find()->where(['category' => $category,'message' => $message])->one();
		// $resSource->getTranslation();
		
		// Bugfix: there's yet no message (maybe a new objecttype?!)
		if (isset($resSource->id) === false) return true;

		// Build string for language master from actual selected perspective
		$language_base   = explode("-",Yii::$app->language)[0];
		$language_master = $language_base . "-Master";
		
		// Get the language id from the master
		$languages = \lajax\translatemanager\models\Language::find()->where(["status" => 1, 'name_ascii' => $language_master])->one();
		$language_id_master = $languages->language_id;
		
		$languageTranslations = new \lajax\translatemanager\models\LanguageTranslate();
		$resTranslation       = $languageTranslations->find()->where(['id' => $resSource->id, 'language' => $languages->language_id])->one();
		$comparison = empty($resTranslation->translation) ? "dummyXYZ" : $resTranslation->translation;
		
        // echo "</pre>";
        // VarDumper::dump(Yii::$app->language);
        // VarDumper::dump($language_base);
        // VarDumper::dump($language_master);
        // VarDumper::dump($language_id_master);
        // VarDumper::dump($languages->language_id);
        // VarDumper::dump($languages->name_ascii);
        // VarDumper::dump($resSource->id);
        // VarDumper::dump(Yii::t($category, $message));
        // VarDumper::dump($comparison);
        // VarDumper::dump($language_master);
		
        // VarDumper::dump($resTranslation);
        // VarDumper::dump(empty($resTranslation->translation) ? "dummyXYZ" : $resTranslation->translation);
        // VarDumper::dump(Yii::t($category, $message) !== $comparison ? true : false);
        // echo "</pre>";
        // die();
		
		// if ($languages->name_ascii === $language_master) return true;
		if ($language_id_master === Yii::$app->language) return true;

		$result = (bool)(Yii::t($category, $message) !== $comparison ? true : false); 
		return $result;
	}
	
	public static function translationExists($category, $message)
	{
		$result = false;
		$languageSources = new \lajax\translatemanager\models\LanguageSource();
		$resSource = $languageSources->find()->where(['category' => $category,'message' => $message])->one();

		// Bugfix: there's yet no message (maybe a new objecttype?!)
		if (isset($resSource->id) === false) return $result;

		$languageTranslations = new \lajax\translatemanager\models\LanguageTranslate();
		$resTranslation       = $languageTranslations->find()->where(['id' => $resSource->id, 'language' => Yii::$app->language])->one();
		

		if (!empty($resTranslation->translation))
		{
			$result = true;
		}
		return (bool)$result;
	}
	
	public static function getReadyToUseNavDropDownElements()
	{
		$navBarItems = array();
		
		$langList = PerspectiveHelper::getLanguageList();
		
		foreach($langList as $languageItem)
		{
			array_push($navBarItems, [
				'url' => ['/site/language', 'language' => $languageItem->language_id,],
				'label' => $languageItem->name . (Yii::$app->language == $languageItem->language_id ? " [X]" : "") , 
				'visible' => True
				]);
		}
		
		return $navBarItems;		
	}
	
	public static function SearchModelFilter($SearchModelClassInstance)
	{
		$perspective_filter_params = array();

		// Check, if model has an attribute named "fk_object_type_id"...
		if (array_key_exists("fk_object_type_id",  $SearchModelClassInstance->attributes) === true)
		{
			// Avoid error when no records exists.
			if (isset($SearchModelClassInstance->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === false) return $perspective_filter_params;

			// Abstract method to get fk_object_type_id for model.
			$fk_object_type_id = $SearchModelClassInstance->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;		
			$model = new \app\models\PerspectiveFilter();
			
			// Read the attribut names
			$distinct_filter_attribute_names = $model::find()->select(['filter_attribute_name'])->distinct()->where(['ref_fk_object_type_id' => $fk_object_type_id, 'fk_language_id' => Yii::$app->language])->all();
			$arr = array();
			foreach($distinct_filter_attribute_names as $distinct_filter_attribute_name)
			{
				// For every attribute name a new array with the elements
				$filter_attributes = $model::find()
						->select(['filter_attribute_name','filter_value'])
						->distinct()
						->where(
								[
									'ref_fk_object_type_id' => $fk_object_type_id,
									'filter_attribute_name' => $distinct_filter_attribute_name->filter_attribute_name,
									'fk_language_id' => Yii::$app->language
								]
							)
						->all();
				
				$arrV = array();
				// id's only get an array at the moment
				if (stripos($distinct_filter_attribute_name->filter_attribute_name, "id") !== false)
				{
					foreach($filter_attributes as $filter_attribute)
					{
						array_push($arrV,$filter_attribute->filter_value);
					}
					$arr[$distinct_filter_attribute_name->filter_attribute_name] = $arrV; 
				}			
				else
				{
					// if not an multiple IN value, then take the first singe value for die LIKE where statement
					$arr[$distinct_filter_attribute_name->filter_attribute_name] = $filter_attributes[0]->filter_value;
				}
			}
			
			$perspective_filter_params[\yii\helpers\StringHelper::basename(get_class($SearchModelClassInstance))] = $arr;
			
		}
						
		return $perspective_filter_params;
	}	
}
?>