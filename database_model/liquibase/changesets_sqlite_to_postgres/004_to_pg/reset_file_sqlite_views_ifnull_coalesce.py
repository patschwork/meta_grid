import os
dir_path = os.path.dirname(os.path.realpath(__file__))
output_file = os.path.join(dir_path, 'create_everything_for_all_views.sql')
f = open(output_file, "w")
f.write("-- This file will be overwritten by the changelog -> sqlite_views_ifnull_coalesce.py\n")
f.write("SELECT 1/0 AS forcing_error")
f.close()