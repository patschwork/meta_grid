import subprocess
import os 
from sys import platform as _platform
import shutil

# # Database connections
database_filepath = os.getenv("METAGRID_SQLITE_PATH", '---') 
postgres_user = os.getenv("METAGRID_POSTGRESQL_USER", '---') 
postgres_password = os.getenv("METAGRID_POSTGRESQL_PASSWORD", '---') 
postgres_host = os.getenv("METAGRID_POSTGRESQL_HOST", '---') 
postgres_port = os.getenv("METAGRID_POSTGRESQL_PORT", 5432) 
postgres_database = os.getenv("METAGRID_POSTGRESQL_DATABASE", '---') 
postgres_path = f"pgsql://{postgres_user}:{postgres_password}@{postgres_host}:{postgres_port}/{postgres_database}"
pgloader_bin = os.getenv("PGLOADER_BIN", "pgloader")

if database_filepath=='---' or postgres_user=='---' or postgres_password=='---' or postgres_host=='---' or postgres_database=='---':
    exit(-1)


dir_path = os.path.dirname(os.path.realpath(__file__))
f = open(os.path.join(dir_path, 'pgloader_output.txt'), "w")

# Check if pgloader can be found
path = shutil.which(pgloader_bin) 
if path is None:
    print(f"no executable found for command '{pgloader_bin}'")
    f.write(f"no executable found for command '{pgloader_bin}'")
    f.close()
    exit(-1)

parameter=[]
parameter.append(f'{pgloader_bin} --with "quote identifiers" {database_filepath} {postgres_path}')
subprocess.run(parameter, shell=True, stdout=f)