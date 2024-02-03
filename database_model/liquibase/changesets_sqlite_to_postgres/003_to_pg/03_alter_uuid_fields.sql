do
$$
declare
  l_rec record;
  l_sql text;
  l_table text;
BEGIN
  -- Do quality-saving metrics with qs_uuid_relations
  -- Change datatype to UUID for non-*log-tables
  -- Set new default value for uuid fields
  for l_rec in 
        SELECT table_schema,table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS C
        WHERE 1=1 
        AND C.COLUMN_NAME LIKE '%uuid' 
        AND table_name NOT LIKE '%_log' 
        AND data_type='text'
  LOOP
    EXECUTE format('SELECT qs_uuid_relations(''%s'', ''before'');',l_rec.table_name);
    l_sql := format('ALTER TABLE %I.%I ALTER COLUMN %I TYPE uuid USING %I::uuid::uuid;', 
                     l_rec.table_schema, 
                     l_rec.table_name, 
                     l_rec.column_name,
                     l_rec.column_name);
    RAISE NOTICE 'execute sql: %', l_sql;                 
    execute l_sql;
    EXECUTE format('SELECT tbl_alter_uuid_default(''%s'');',l_rec.table_name);
  end loop;
  
  -- Change datatype to UUID for *log-tables
  for l_rec in 
        SELECT table_schema,table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS C
        WHERE 1=1 
        AND C.COLUMN_NAME LIKE '%uuid%' 
        AND table_name LIKE '%_log' 
        AND data_type='text'
  LOOP
    l_sql := format('ALTER TABLE %I.%I ALTER COLUMN %I TYPE uuid USING %I::uuid::uuid;', 
                     l_rec.table_schema, 
                     l_rec.table_name, 
                     l_rec.column_name,
                     l_rec.column_name);
    RAISE NOTICE 'execute sql: %', l_sql;                 
    execute l_sql;
  end loop;
  
  -- Do quality-saving metrics with qs_uuid_relations
  for l_rec in 
        SELECT table_schema,table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS C
        WHERE 1=1 
        AND C.COLUMN_NAME LIKE '%uuid' 
        AND table_name NOT LIKE '%_log' 
        AND data_type='uuid'
  LOOP
    EXECUTE format('SELECT qs_uuid_relations(''%s'', ''after'');',l_rec.table_name);
  end loop;
end;  
$$
;