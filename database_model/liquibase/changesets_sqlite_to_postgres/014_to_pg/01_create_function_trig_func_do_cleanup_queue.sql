CREATE OR REPLACE FUNCTION public.trig_func_do_cleanup_queue()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id)
   SELECT id, fk_object_type_id FROM old_table;
   RETURN NULL;
END;
$function$
;