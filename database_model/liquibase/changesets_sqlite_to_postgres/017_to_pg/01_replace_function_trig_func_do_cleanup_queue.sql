CREATE OR REPLACE FUNCTION public.trig_func_do_cleanup_queue()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    BEGIN
       INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id)
       SELECT id, fk_object_type_id FROM old_table;
    EXCEPTION
        WHEN others THEN
        BEGIN
            RAISE NOTICE 'error at: %', 'Could not insert into cleanup_queue'; 
            IF (SELECT to_jsonb(rows) FROM (SELECT * FROM old_table) rows)::text IS NULL THEN  -- lower-case!
                RAISE NOTICE 'old_table is NULL...';
            END IF;
        END;
    END;
   RETURN NULL;
END;
$function$
;
