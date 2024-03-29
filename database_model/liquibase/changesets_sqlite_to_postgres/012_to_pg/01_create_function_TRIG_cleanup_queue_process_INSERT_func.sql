CREATE OR REPLACE FUNCTION TRIG_cleanup_queue_process_INSERT_func()
  RETURNS TRIGGER 
  LANGUAGE PLPGSQL
  AS
$$
BEGIN
    RAISE NOTICE 'Started: %', 1;
    -- Generic cleanup (if new.ref_fk_object_id=-1 AND new.ref_fk_object_type_id=-1)
    DELETE FROM map_object_2_object WHERE id IN
    (
        SELECT DISTINCT mo2o.id FROM map_object_2_object mo2o
        INNER JOIN "v_All_Mappings_Union" vALU ON 1=1
            AND vALU.listkey IS NULL
            AND mo2o.ref_fk_object_id_1=vALU.ref_fk_object_id_1
            AND mo2o.ref_fk_object_id_2=vALU.ref_fk_object_id_2
            AND mo2o.ref_fk_object_type_id_1=vALU.ref_fk_object_type_id_1
            AND mo2o.ref_fk_object_type_id_2=vALU.ref_fk_object_type_id_2
        WHERE -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id
    )
    AND -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id    
    ;


    DELETE FROM object_comment WHERE id IN 
    (
        SELECT oc.id FROM object_comment oc
        LEFT JOIN "v_All_Objects_Union" vAOU ON 1=1
            AND oc.ref_fk_object_id = vAOU.id
            AND oc.ref_fk_object_type_id = vAOU.fk_object_type_id
        WHERE vAOU.id IS NULL
            AND -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id
    )
    AND -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id    
    ;

    DELETE FROM db_table_field WHERE id IN 
        (
            SELECT dbtf.id FROM db_table_field dbtf
            LEFT JOIN db_table dbt ON 1=1
               AND dbtf.fk_db_table_id = dbt.id
            WHERE 1=1 
               AND dbt.id IS NULL
               AND -1=new.ref_fk_object_id
               AND -1=new.ref_fk_object_type_id
        )
    AND -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id    
    ;

    -- Delete mappings
    DELETE FROM map_object_2_object WHERE (ref_fk_object_id_1=new.ref_fk_object_id AND ref_fk_object_type_id_1=new.ref_fk_object_type_id) OR ((ref_fk_object_id_2=new.ref_fk_object_id AND ref_fk_object_type_id_2=new.ref_fk_object_type_id));
    
    -- Delete comments
    DELETE FROM object_comment WHERE ref_fk_object_id=new.ref_fk_object_id AND ref_fk_object_type_id=new.ref_fk_object_type_id;
    
    -- If a db_table was deleted, then also clean up child elements -> db_table_field
    -- Not needed: Is handled in Yii2 frontend -> controller of DbtableController
    -- DELETE FROM db_table_field WHERE fk_db_table_id=new.ref_fk_object_id AND new.ref_fk_object_type_id=4;
    
    -- If a bracket was deleted, then also clean up child elements -> bracket_searchPattern
    -- DELETE FROM bracket_searchPattern WHERE fk_bracket_id=new.ref_fk_object_id AND new.ref_fk_object_type_id=16;
    
    -- If a attribut was deleted, then comment this to the corresponding bracket
    -- INSERT INTO object_comment (ref_fk_object_id, ref_fk_object_type_id, comment)
    -- SELECT 
    --    bracket.id AS ref_fk_object_id
    --   ,bracket.fk_object_type_id AS ref_fk_object_type_id
    --   ,'Systemmessage: Referenced attribut was set to NULL because it was deleted (id=' || CAST(attribute.id AS VARCHAR(100)) || ', name=' || attribute.name || ')'
    -- FROM bracket
    -- INNER JOIN attribute ON attribute.id = bracket.fk_attribute_id AND attribute.id=new.ref_fk_object_id AND attribute.fk_object_type_id=ref_fk_object_type_id
    -- ;
    
    -- If a attribute was deleted, then update the bracket (because of fk_attribute_id)
    -- UPDATE bracket
    --    SET fk_attribute_id=NULL
    -- WHERE fk_attribute_id=new.ref_fk_object_id AND new.ref_fk_object_type_id=9;
    
    -- Following FKs not handled:
    -- client -> project -> etc.
    -- fk_contact_group_id in contact
    -- fk_data_delivery_type_id in data_delivery_object
    -- fk_tool_id in db_database
    
    DELETE FROM cleanup_queue WHERE (id=new.id) OR (ref_fk_object_type_id=12 AND -1=new.ref_fk_object_id AND -1=new.ref_fk_object_type_id);
    RETURN NEW;
END;
$$
