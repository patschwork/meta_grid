CREATE OR REPLACE FUNCTION rollout_all_trigger_4_do_cleanup_queue()
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
                AND T.table_name <> 'cleanup_queue_log'
  loop
    l_sql_1 := format('DROP TRIGGER IF EXISTS do_cleanup_queue ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_1;                 
    execute l_sql_1;
    l_sql_2 := format(
        '
        CREATE TRIGGER do_cleanup_queue AFTER
        DELETE
            ON
            %I.%I
            REFERENCING OLD TABLE AS old_table 
            FOR EACH STATEMENT 
            EXECUTE FUNCTION trig_func_do_cleanup_queue();
        ',l_rec.table_schema 
         ,l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_2;                 
    execute l_sql_2;
  end loop;
end;  
$function$
;