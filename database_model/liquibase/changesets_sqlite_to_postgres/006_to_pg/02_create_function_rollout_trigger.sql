CREATE OR REPLACE FUNCTION rollout_trigger()
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
  l_rec record;
  l_sql_1 text;
  l_sql_2 text;
  l_sql_3 text;
  l_sql_4 text;
begin
  for l_rec in  SELECT table_schema,REPLACE(T.table_name, '_log', '') AS table_name 
                FROM INFORMATION_SCHEMA.TABLES T
                WHERE 1=1 
                AND T.table_name LIKE '%\_log'
                AND EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES T_exists WHERE T_exists.table_name=REPLACE(T.table_name, '_log', ''))
  loop
    l_sql_1 := format('DROP TRIGGER IF EXISTS trig_log_insupd ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_1;                 
    execute l_sql_1;
    l_sql_2 := format(
        '
        CREATE TRIGGER trig_log_insupd AFTER
        INSERT
            OR
        UPDATE
            ON
            %I.%I
            FOR EACH ROW
            WHEN ((pg_trigger_depth() = 0)) EXECUTE FUNCTION trig_func_log();
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_2;                 
    execute l_sql_2;
    l_sql_3 := format('DROP TRIGGER IF EXISTS trig_log_del ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_3;                 
    execute l_sql_3;
    l_sql_4 := format(
        '
        CREATE TRIGGER trig_log_del BEFORE
        DELETE
            ON
            %I.%I
            FOR EACH ROW
            WHEN ((pg_trigger_depth() = 0)) EXECUTE FUNCTION trig_func_log();
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_4;                 
    execute l_sql_4;
  end loop;
end;  
$function$
;