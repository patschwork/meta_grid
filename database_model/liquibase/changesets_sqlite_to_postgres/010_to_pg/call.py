import subprocess
import os 
from sys import platform as _platform
import sqlite3
import pandas as pd
from sqlalchemy import create_engine
import psycopg2
import sys

def create_connection(db_file):
    """ create a database connection to the SQLite database
        specified by the db_file
    :param db_file: database file
    :return: Connection object or None
    """
    conn = None
    try:
        conn = sqlite3.connect(db_file)
    except Exception as e:
        print(e)

    return conn

# https://github.com/NaysanSaran/pandas2postgresql/blob/master/src/bulk_insert_execute_values.py
def create_connect_postgres(pg_url):
    """ Connect to the PostgreSQL database server """
    engine = create_engine(pg_url)
    return engine

# # Database connections
database_filepath = os.getenv("METAGRID_SQLITE_PATH", '---') 
pg_user = os.getenv("METAGRID_POSTGRESQL_USER", '---') 
pg_pwd = os.getenv("METAGRID_POSTGRESQL_PASSWORD", '---') 
pg_host = os.getenv("METAGRID_POSTGRESQL_HOST", '---') 
pg_port = os.getenv("METAGRID_POSTGRESQL_PORT", 5432) 
pg_db = os.getenv("METAGRID_POSTGRESQL_DATABASE", '---') 

if database_filepath=='---' or pg_user=='---' or pg_pwd=='---' or pg_host=='---' or pg_db=='---':
    exit(-1)

postgres_url = f'postgresql+psycopg2://{pg_user}:{pg_pwd}@{pg_host}:{pg_port}/{pg_db}'

dir_path = os.path.dirname(os.path.realpath(__file__))


conn = create_connection(database_filepath)
sql1 = """
SELECT * FROM auth_item;
"""
df1 = pd.read_sql(sql=sql1, con=conn)

sql2 = """
SELECT * FROM auth_item_child;
"""
df2 = pd.read_sql(sql=sql2, con=conn)

conn_pgsql = create_connect_postgres(postgres_url)

df1.to_sql(con=conn_pgsql, if_exists='append', index=False, name='auth_item')
df2.to_sql(con=conn_pgsql, if_exists='append', index=False, name='auth_item_child')