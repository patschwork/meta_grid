CREATE OR REPLACE FUNCTION public.trig_func_log_ins_upd_del()
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
  sql_stm1 TEXT;
BEGIN
    BEGIN
        
        IF current_setting('flags.'||TG_TABLE_NAME, TRUE) = 'DO_UPDATE' OR TG_OP<>'UPDATE' THEN
            sql_stm1 := (SELECT REPLACE(REPLACE(sql_statement, '{{TABLENAME}}', CASE WHEN TG_OP IN ('INSERT', 'UPDATE') THEN 'NEW' ELSE 'OLD' END), '{{TG_OP}}', TG_OP) FROM v_prepared_sql_for_log_trigger WHERE TABLE_NAME=TG_TABLE_NAME::text);
            
            EXECUTE sql_stm1;
            
            PERFORM set_config('flags.'||TG_TABLE_NAME, '---', FALSE);
        END IF;

    EXCEPTION
        WHEN others THEN
        BEGIN
            RAISE NOTICE 'error at: %', CONCAT('Could not insert into ', TG_TABLE_NAME::TEXT, '_log'); 
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
            END;
    END;
   RETURN NULL;
END;
$function$
;