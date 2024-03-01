CREATE OR REPLACE FUNCTION public.trig_func_do_cleanup_queue()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    _sql_state TEXT;
    _message TEXT;
    _detail TEXT;
    _hint TEXT;
    _context TEXT;
    _stack text; 
    _fcesig text;
BEGIN
    BEGIN
       IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=TG_TABLE_NAME AND COLUMN_NAME = 'fk_object_type_id') THEN
--       IF TG_TABLE_NAME<>'map_object_2_object' THEN
           INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id)
           SELECT id, fk_object_type_id FROM old_table;
       END IF;
    EXCEPTION
        WHEN others THEN
        BEGIN
            RAISE NOTICE 'error at: %', 'Could not insert into cleanup_queue';
            GET STACKED DIAGNOSTICS
                _sql_state := RETURNED_SQLSTATE,
                _message := MESSAGE_TEXT,
                _detail := PG_EXCEPTION_DETAIL,
                _hint := PG_EXCEPTION_HINT,
                _context := PG_EXCEPTION_CONTEXT;
            
              GET DIAGNOSTICS _stack = PG_CONTEXT;
              _fcesig := substring(_stack from 'function (.*?) line');
                          
    
            INSERT INTO trigger_errors (sql_state, message, detail, hint, context, trigger_name, function_name, table_name)
            VALUES (_sql_state, _message, _detail, _hint, _context, TG_NAME, _fcesig::regprocedure::text, TG_TABLE_NAME);
            IF (SELECT to_jsonb(rows) FROM (SELECT * FROM old_table) rows)::text IS NULL THEN  
                RAISE NOTICE 'old_table is NULL...';
            END IF;
        END;
    END;
   RETURN NULL;
END;
$function$
;