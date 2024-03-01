CREATE OR REPLACE FUNCTION public.dbdeploy_remove_log_trigger()
 RETURNS void
 LANGUAGE plpgsql
AS $function$
DECLARE
  l_rec record;
  l_sql_1 text;
  l_sql_2 text;
  l_sql_3 text;
  l_sql_4 text;
BEGIN
  FOR l_rec IN  SELECT table_schema,REPLACE(T.table_name, '_log', '') AS table_name 
                FROM INFORMATION_SCHEMA.TABLES T
                WHERE 1=1 
                AND T.table_name LIKE '%_log'
                AND EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES T_exists WHERE T_exists.table_name=REPLACE(T.table_name, '_log', ''))
  LOOP
    l_sql_1 := format('DROP TRIGGER IF EXISTS trig_log_ins ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_1;
    BEGIN
        EXECUTE l_sql_1;
    EXCEPTION
        WHEN others THEN
            RAISE NOTICE 'error at: %', l_sql_1;
    END;

    l_sql_2 := format('DROP TRIGGER IF EXISTS trig_log_upd ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_2;
    BEGIN
        EXECUTE l_sql_2;
    EXCEPTION
        WHEN others THEN
            RAISE NOTICE 'error at: %', l_sql_2;
    END;

    l_sql_3 := format('DROP TRIGGER IF EXISTS trig_log_del ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_3;
    BEGIN
        EXECUTE l_sql_3;
    EXCEPTION
        WHEN others THEN
            RAISE NOTICE 'error at: %', l_sql_3;
    END;

    l_sql_4 := format('DROP TRIGGER IF EXISTS trig_before_upd ON %I.%I;', 
                     l_rec.table_schema, 
                     l_rec.table_name);
    RAISE NOTICE 'execute sql: %', l_sql_4;
    BEGIN
        EXECUTE l_sql_4;
    EXCEPTION
        WHEN others THEN
            RAISE NOTICE 'error at: %', l_sql_4;
    END;

  END LOOP;
END;  
$function$
;
