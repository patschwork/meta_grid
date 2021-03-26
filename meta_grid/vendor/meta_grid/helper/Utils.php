<?php

namespace vendor\meta_grid\helper;

use yii\db\Query;

/**
 * Util helper for meta#grid
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 2.4.1
 * 
 */
class Utils
{
    /**
     * Gets the application config parameter from the database table app_config
     *
     * @author meta#grid (Patrick Schmitz)
     * @since 2.4.1
     * 
     */

     public function get_app_config($key)
     {

        $default_value = null;

        if ($key=="liquibase_changelog_master_filepath") $default_value="../../../../database_model/liquibase/db.changelog-master.xml";
        if ($key=="web_app_header_bckcolor") $default_value="";
        if ($key=="web_app_header_brandlabel_additional_text") $default_value="";
        if ($key=="db_table_show_buttons_for_different_object_type_updates") $default_value=0;
        if ($key=="floatthead_for_gridviews") $default_value=1;
        if ($key=="cache_duration_mappings_list") $default_value=3600*48;
        if ($key=="globalsearch_init_empty") $default_value=1;
        if ($key=="importstage_dbtable_processing_time_limit") $default_value=2000;
        if ($key=="importstage_dbtable_processing_memory_limit") $default_value=1024;
        if ($key=="import_processing_time_limit") $default_value=2000;
        if ($key=="import_processing_memory_limit") $default_value=1024;
        if ($key=="mapper_createext_time_limit") $default_value=2000;
        if ($key=="mapper_createext_memory_limit") $default_value=1024;

        $res_arr = ((new Query())->from('app_config')->select(['valueSTRING', 'valueINT'])->where(["key" => $key])->one());      

        $valueSTRING = $res_arr['valueSTRING'];
        $valueINT = $res_arr['valueINT'];

        if (! $res_arr)
        {
            return $default_value;
        }

        if ((is_null($valueSTRING) ||  $valueSTRING === "" || empty($valueSTRING)) && (is_null($valueINT)))
        {
            return $default_value;
        }
        else
        {
            if (is_null($valueINT))
            {
                return $valueSTRING;
            }
            else
            {
                return intval($valueINT);
            }
        }

        return null;
     }
}