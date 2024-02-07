
# Set Yii2 db-Config?!


# local storage of InquirerPy
from InquirerPy import prompt
from InquirerPy.validator import NumberValidator
import sys
import base_lib
import base_installations
from subprocess import check_call, CalledProcessError
from InquirerPy.validator import PathValidator
import os
import datetime

def bla(msg, action=None, withLooging=False, wait=False):
    base_lib.bla(msg, action, False, None, None)
    if (wait):
        if (int(sys.version_info[0]) >= 3):
            dummy = input("")
        else:
            dummy = raw_input("")

# Do Ubuntu (or derivates) related stuff (mainly because of the package manager apt)
def inst_ubuntu():
    if base_installations.prompt_sudo() != 0:
        base_lib.bla("The installation process needs sudo rights to install system components.", action="error")
        base_lib.bla("You may also start the installer with sudo or as root.")
        return False
    else:
        software_packages = "sudo apt-get install -y pgloader php-pgsql python3-pip"
        try:
            base_lib.bla(msg="- Install needed software packages via apt-get install", action="header")
            base_lib.bla(msg=software_packages)
            base_installations.check_call(software_packages.split(" "), stdout=open(os.devnull,'wb'))
            return True
        except CalledProcessError as e:
            base_lib.bla(msg=e.output, action="error")
            return False

currentFile = __file__
realPath = os.path.realpath(currentFile)  # /home/user/test/my_script.py
dirPath = os.path.dirname(realPath)  # /home/user/test
DATETIMENOW = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")

# prepare reading install_settings.ini file
ini_user_settings_path = os.path.join(dirPath,"install_settings.ini")

offer_pg_user = base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'postgresql_user', "pg_metagrid_usr")
offer_pg_pwd = base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'postgresql_password', r"pg_metagrid_pwd")
offer_pg_hostname = base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'postgresql_host', "localhost")
offer_pg_port = base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'postgresql_port', "5432")
offer_pg_dbname = base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'postgresql_database', "metagrid")
offer_location_lqb_changelog = base_lib.get_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'location_lqb_changelog', dirPath)
offer_location_deploy_py = base_lib.get_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'location_deploy_py', dirPath)
already_done = base_lib.get_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'migration_done', "-No-")

if already_done=='Yes':
    bla(msg='Migration SQLite to PostgreSQL was already done!', action="error")

ioc = base_lib.get_installation_os_candidate()

print("Please enter the configuration for the PostgreSQL database server:")
questions = [
    {"type": "confirm", "message": "Show SQL commands to setup database before migration start?", "name": "show_sql", "default": True},
    {"type": "input", "message": "Hostname:", "name": "pg_hostname", "default": offer_pg_hostname},
    {"type": "input", "message": "Port:", "name": "pg_port", "validate": NumberValidator(), "default": offer_pg_port},
    {"type": "input", "message": "Database:", "name": "pg_dbname", "default": offer_pg_dbname},
    {"type": "input", "message": "Postgres user:", "name": "pg_user", "default": offer_pg_user},
    {"type": "password", "message": "Postgres password:", "name": "pg_pwd", "default": offer_pg_pwd},
    {"type": "filepath", "message": "Location for db.changelog-sqlite_to_postgres.xml:", "name": "location_lqb_changelog", "default": offer_location_lqb_changelog, "validate": PathValidator(is_file=True, message="Input is not a file"), "only_files": False},
    {"type": "filepath", "message": "Location for deploy.py:", "name": "location_deploy.py", "default": offer_location_deploy_py, "validate": PathValidator(is_file=True, message="Input is not a file"), "only_files": False},
]
result = prompt(questions)

if not os.path.isfile(result["location_lqb_changelog"]):
    bla(msg="db.changelog-sqlite_to_postgres.xml not found!", action="error")
    sys.exit(-1)

if not os.path.isfile(result["location_deploy.py"]):
    bla(msg="deploy.py not found!", action="error")
    sys.exit(-1)

folderfile_Fresh_LQB_DeploymentTool = result["location_deploy.py"]

create_database_sql_templace = f"""
CREATE USER {result["pg_user"]} WITH PASSWORD '**** PLEASE INSERT THE PASSWORD HERE ****';
CREATE DATABASE {result["pg_dbname"]}
 ENCODING 'UTF8'
 LC_COLLATE='C'
 LC_CTYPE='C'
 template=template0
 OWNER {result["pg_user"]};
GRANT ALL PRIVILEGES ON DATABASE {result["pg_dbname"]} to {result["pg_user"]};
"""

if result["show_sql"]:
    print(create_database_sql_templace)


if ioc[0] not in ["ubuntu"]:
    msg="""
The migration assistant currently only supports Ubuntu Linux and derivates.
You can try to continue the installation process, but unfortunately it might fail and abort with errors.
Please make sure, the following components are installed
- pgloader
- PHP PostgreSQL driver 
- Python 3 pip
    """
    bla(msg=msg, action="warning")

questions = [
    {"type": "confirm", "message": "Ready and start?", "name": "ready", "default": False},
]
result2 = prompt(questions)

if not result2["ready"]:
    sys.exit(0)

if ioc[0]=="ubuntu":
    if inst_ubuntu():
        pass


folder_WorkingDir = dirPath

# read existing settings
folderfile_Database=base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'folderfile_Database', "---")
liquibasePathExe=base_lib.get_user_settings(ini_user_settings_path, "tools", 'liquibasePathExe', "---")
pythonExe=base_lib.get_user_settings(ini_user_settings_path, "tools", 'pythonExe', "---")
folder_Frontend=base_lib.get_user_settings(ini_user_settings_path, "current_installation", 'folder_Frontend', "---")


# Set env variables
os.environ["METAGRID_POSTGRESQL_USER"]=result["pg_user"]
os.environ["METAGRID_POSTGRESQL_PASSWORD"]=result["pg_pwd"]
os.environ["METAGRID_POSTGRESQL_HOST"]=result["pg_hostname"]
os.environ["METAGRID_POSTGRESQL_PORT"]=result["pg_port"]
os.environ["METAGRID_POSTGRESQL_DATABASE"]=result["pg_dbname"]
os.environ["METAGRID_SQLITE_PATH"]=folderfile_Database


# write back to install_settings.ini
base_lib.set_user_settings(ini_user_settings_path, "current_installation", 'postgresql_user', result["pg_user"])
base_lib.set_user_settings(ini_user_settings_path, "current_installation", 'postgresql_password', result["pg_pwd"])
base_lib.set_user_settings(ini_user_settings_path, "current_installation", 'postgresql_host', result["pg_hostname"])
base_lib.set_user_settings(ini_user_settings_path, "current_installation", 'postgresql_port', result["pg_port"])
base_lib.set_user_settings(ini_user_settings_path, "current_installation", 'postgresql_database', result["pg_dbname"])
base_lib.set_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'location_lqb_changelog', result["location_lqb_changelog"])
base_lib.set_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'location_deploy_py', result["location_deploy.py"])


# Change the working directory (where deploy.py is located)
import os
pathname_from_deploypy = os.path.dirname(os.path.abspath(result["location_deploy.py"]))
os.chdir(pathname_from_deploypy)

# Prepare LiquiBase deployment
bla("- Create config file for LiquiBase database deployment", "header", False)
lqb_ini_file = "deploy_config_LQB_sqlite_to_postgres_" + DATETIMENOW + ".ini"
dynConfigIni = os.path.join(folder_WorkingDir, lqb_ini_file)

# abspath_db = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folderfile_Database), os.path.basename(folderfile_Database))
abs_folderfile_Fresh_LQB_Changelog = base_lib.getFilePathRelativeScriptPath(os.path.dirname(result["location_lqb_changelog"]), os.path.basename(result["location_lqb_changelog"]))

# Generate ini-file for the LiquiBase deployment
try:
    base_lib.createLQBConfigDeployIniFile(cfgFile=dynConfigIni, 
                                          liquibasePathExe=liquibasePathExe, 
                                          liquibaseChangeLogFile=abs_folderfile_Fresh_LQB_Changelog, 
                                          dbpath=f'//{result["pg_hostname"]}:{result["pg_port"]}/{result["pg_dbname"]}',
                                          liquibaseDriver="org.postgresql.Driver",
                                          liquibaseAction='updateCount',
                                          liquibaseActionValue='30',
                                          liquibaseDriverUrlprefix=r'jdbc:postgresql:%(dbpath)s',
                                          comment="Generated for SQLite to PostgreSQL migration",
                                          dbuser=result["pg_user"],
                                          dbpassword=result["pg_pwd"],
                                        #   additional_liquibase_parameter={
                                        #       'path_to_database_helper_folder': 'test'
                                        #   }
                                          )
    bla(msg="Successful: Created config file for LiquiBase deployment (" + dynConfigIni + ")", action="OK", withLooging=False)
except Exception as e:
    errMsg="Error on creating a deployment config file for LiquiBase deployment! LiquiBase deployment will not work correctly. Installatation/Update not completed!"
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    sys.exit(-1)
    
# Check Liquibase
bla("- Start LiquiBase database deployment", "header", False)
try:
    if (not os.path.exists(liquibasePathExe)):
        raise Exception("LiquiBase executable (" + liquibasePathExe + ") not found! Installatation/Update not completed!")
except Exception as e:
    bla(str(e), "error", True, True)
    exit()

# exec LiquiBase Deployment script (also in Python)
try:
    javaexe=base_lib.get_user_settings(ini_user_settings_path, "tools", 'javaexe', "")
    if (javaexe != ""):
        java_home = os.path.sep.join((javaexe.split(os.path.sep)[:-2]))
        os.environ["JAVA_HOME"] = java_home
    os.environ["JAVA_OPTS"] = "-Duser.language=en"
    returnVal=base_lib.LQB_exec(pythonExe=pythonExe, folderfile_Fresh_LQB_DeploymentTool=result["location_deploy.py"], envkey="LQB_sqlite_to_postgres_" + DATETIMENOW, configpath=base_lib.getFilePathRelativeScriptPath(folder_WorkingDir,''))
    if ("Liquibase Update Successful" in returnVal):
        bla(returnVal, "OK", True)
    elif ("Update has been successful" in returnVal):
        bla(returnVal, "OK", True)    
    elif ("Liquibase command 'updateCount' was executed successfully" in returnVal):
        bla(returnVal, "OK", True)
    else:
        bla("The LiquiBase deployment did not quit with a successful comment...: " + returnVal, "NOK", True, True)
        sys.exit(-1)
except Exception as e:
    errMsg="Error occured on database deployment! Please check the logs of the LiquiBase deployment helper (" + os.path.dirname(folderfile_Fresh_LQB_DeploymentTool) + ")."
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    raise Exception(errMsg)

yii2_db_config_template = f"""
    'dsn' => 'pgsql:host={result["pg_hostname"]};dbname={result["pg_dbname"]};port={result["pg_port"]}',
    'username' => '{result["pg_user"]}',
    'password' => '**** PLEASE INSERT THE PASSWORD HERE ****',
"""
yii2_db_config_old_settings_example = """
    // 'dsn' => 'sqlite:../../../../dwh_meta.sqlite',	
    // 'on afterOpen' => function($event) {
    //    $event->sender->createCommand("PRAGMA foreign_keys = ON")->execute();
    //     },
"""
bla(f"Please edit your frontend database settings ({folder_Frontend}/config/db.php):")
print(yii2_db_config_template)

bla("...and commment or delete the settings for SQLite as in the example below:")
print(yii2_db_config_old_settings_example)

# Everything worked good
base_lib.set_user_settings(ini_user_settings_path, "migration_sqlite_to_postgres", 'migration_done', "Yes")

# cleanup
os.unlink(dynConfigIni)

bla(msg="Migration finished", action="OK")
