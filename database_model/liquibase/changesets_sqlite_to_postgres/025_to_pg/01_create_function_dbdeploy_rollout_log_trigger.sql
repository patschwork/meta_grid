CREATE OR REPLACE FUNCTION public.dbdeploy_rollout_log_trigger()
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
  l_rec record;
  l_sql_1 text;
  l_sql_2 text;
  l_sql_3 text;
  l_sql_4 text;
  l_sql_5 text;
  l_sql_6 text;
  l_sql_7 text;
  l_sql_8 text;
begin
  for l_rec in  SELECT table_schema,REPLACE(T.table_name, '_log', '') AS table_name 
                FROM INFORMATION_SCHEMA.TABLES T
                WHERE 1=1 
                AND T.table_name LIKE '%\_log'
                AND EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES T_exists WHERE T_exists.table_name=REPLACE(T.table_name, '_log', ''))
                AND T.table_name<>'cleanup_queue_log'
  loop
    l_sql_1 := format('DROP TRIGGER IF EXISTS trig_log_ins ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_1;   
    execute l_sql_1;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_2 := format(
        '
        CREATE TRIGGER trig_log_ins AFTER
        INSERT
        ON
        %I.%I 
        REFERENCING NEW TABLE AS NEW FOR EACH STATEMENT
        WHEN ((pg_trigger_depth() = 0)) EXECUTE FUNCTION trig_func_log_ins_upd_del()
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_2;                 
    execute l_sql_2;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_3 := format('DROP TRIGGER IF EXISTS trig_log_upd ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_3;                 
    execute l_sql_3;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_4 := format(
        '
        CREATE TRIGGER trig_log_upd AFTER
        UPDATE
        ON
        %I.%I 
        REFERENCING NEW TABLE AS NEW FOR EACH STATEMENT
        WHEN ((pg_trigger_depth() = 0)) EXECUTE FUNCTION trig_func_log_ins_upd_del()
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_4;                 
    execute l_sql_4;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_5 := format('DROP TRIGGER IF EXISTS trig_log_del ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_5;                 
    execute l_sql_5;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_6 := format(
        '
        CREATE TRIGGER trig_log_del AFTER
        DELETE
        ON
        %I.%I  
        REFERENCING OLD TABLE AS OLD FOR EACH STATEMENT
        WHEN ((pg_trigger_depth() = 0)) EXECUTE FUNCTION trig_func_log_ins_upd_del()
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_6;                 
    execute l_sql_6;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_7 := format('DROP TRIGGER IF EXISTS trig_before_upd ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_7;                 
    execute l_sql_7;
    -- ----------------------------------------------------------------------------------------------------------------------------------------
    l_sql_8 := format(
        '
        CREATE TRIGGER trig_before_upd BEFORE
        UPDATE
        ON
        %I.%I  
        FOR EACH ROW EXECUTE FUNCTION trig_func_log_upd_uuid()
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_8;                 
    execute l_sql_8;

  end loop;
end;  
$function$
;
