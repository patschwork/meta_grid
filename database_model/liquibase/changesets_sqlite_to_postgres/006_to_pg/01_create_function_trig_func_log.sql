CREATE OR REPLACE FUNCTION trig_func_log()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    new_uuid uuid;
    tmp_table text;
    uuid_fields text;
    tmp_table_fields text;
    insert_into_log_stmt text;
    upd_uuid_for_tmptable_stmt text;
    upd_uuid_for_table_stmt text;
BEGIN
   new_uuid := (md5(random()::text || clock_timestamp()::text)::uuid);
   tmp_table := TG_TABLE_NAME::text || '_' || REPLACE(new_uuid::text, '-', '') || '_' || LOWER(LEFT(TG_OP, 1));
   IF TG_OP<>'DELETE' THEN 
    uuid_fields := (SELECT REPLACE(uuid_columns, '[id=?]', CONCAT('uuid=''', NEW.uuid::TEXT, '''')) FROM v_fk_columns_for_triggers WHERE table_name=TG_TABLE_NAME);
    EXECUTE format('CREATE TEMP TABLE %I AS SELECT $1.*, $2 AS log_action' || ',' || uuid_fields, tmp_table)
    USING NEW, TG_OP;
   ELSE
    uuid_fields := (SELECT REPLACE(uuid_columns, '[id=?]', CONCAT('uuid=''', OLD.uuid::TEXT, '''')) FROM v_fk_columns_for_triggers WHERE table_name=TG_TABLE_NAME);
    EXECUTE format('CREATE TEMP TABLE %I AS SELECT $1.*, $2 AS log_action' || ',' || uuid_fields, tmp_table)
    USING OLD, TG_OP;   
   END IF;
   IF TG_OP='UPDATE' THEN
    upd_uuid_for_table_stmt := format('UPDATE %I SET uuid=''%I'' WHERE id=%I', TG_TABLE_NAME, new_uuid::text, NEW.id::text);
    upd_uuid_for_table_stmt := REPLACE(upd_uuid_for_table_stmt, '"', '');
    EXECUTE upd_uuid_for_table_stmt;
    upd_uuid_for_tmptable_stmt := format('UPDATE %I SET uuid=''%I''', tmp_table, new_uuid);
    upd_uuid_for_tmptable_stmt := REPLACE(upd_uuid_for_tmptable_stmt, '"', '');
    EXECUTE upd_uuid_for_tmptable_stmt;
   END IF;
   tmp_table_fields := (SELECT "columns" FROM v_fk_columns_for_triggers WHERE table_name=tmp_table);
   insert_into_log_stmt := format('INSERT INTO %I (%I) SELECT %I FROM %I', TG_TABLE_NAME||'_log', tmp_table_fields, tmp_table_fields, tmp_table);
   insert_into_log_stmt := REPLACE(insert_into_log_stmt, '"', '');
   EXECUTE insert_into_log_stmt;
   RETURN OLD;  -- wichtig für das DELETE (ohne den RETURN wird der Datensatz nicht gelöscht)
END;
$function$
;