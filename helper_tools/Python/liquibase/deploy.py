#!/usr/bin/python
# -*- coding: utf-8 -*-
# deploy_linux.py 
# deploy liquibase changelogs
# on OS=linux
import os
import sys
if (int(sys.version_info[0]) >= 3):
    import configparser as ConfigParser
else:
    import ConfigParser
import subprocess
import datetime

CONFIGFOLDER = ""
DATETIMENOW = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")

DEBUG = False
if os.getenv("LQB_DEBUG", "No")!="No":
    DEBUG = True

# Command Line Parameters will be detected here
def cli_params():
    global ENVKEY
    global CONFIGFOLDER
    print(sys.argv)
    if len(sys.argv) < 2:
        print("You must call me like:")
        print("   deploy.py <Environment Key (e.g. DEV)>")
        raise Exception("Call parameter missing!")
        sys.exit(1)
    ENVKEY = sys.argv[1]
    try:
        CONFIGFOLDER = sys.argv[2]
    except:
        print("CONFIGFOLDER not given. Using default")
        
    try:
        param_DEBUG = sys.argv[3]
        if param_DEBUG!="":
            DEBUG = True
    except:
        pass

if DEBUG:
    print("DEBUG Mode = ON")

def ConfigSectionMap(section):
    dict1 = {}
    options = Config.options(section)
    for option in options:
        try:
            dict1[option] = Config.get(section, option)
            if dict1[option] == -1:
                print("skip: %s" % option)
        except:
            print("exception on %s!" % option)
            dict1[option] = None
    return dict1

def getConfigFilePath(CONFIGFOLDER, ENVKEY):
    return os.path.join(CONFIGFOLDER,"deploy_config_" + ENVKEY + ".ini")

def writeOutputLog(logpath, msg):
	filename = os.path.join(logpath, "log_" + DATETIMENOW + ".txt")
	myFile = open(filename,"a") 
	myFile.write(msg)
	myFile.close() 
    
def printCurrentEnvSettings(prop, value):
    print(prop + ": "  + value)
    writeOutputLog(logfilepath, prop + ": "  + value + "\n")
	
def getFilePathRelativeScriptPath(filepath):
    return os.path.abspath(os.path.join(dirPath, filepath))

def getAdditionalLiquibaseParameter(Config, section='additional_liquibase_parameter'):
    additional_liquibase_parameter_found = False
    params = dict()
    try:
        params = dict(ConfigNoEnv.items(section))
        additional_liquibase_parameter_found = True
    except:
        pass
    if params==dict():
        additional_liquibase_parameter_found = False
    return additional_liquibase_parameter_found, params

# if (int(sys.version_info[0]) < 3):
#     print("You need Python 3 to run this script!")
#     sys.exit(1)


cli_params()


if (os.path.isfile(getConfigFilePath(CONFIGFOLDER, ENVKEY))):
    print("Config file found: " + getConfigFilePath(CONFIGFOLDER, ENVKEY))
else:
    raise Exception("Config file not found!")
    sys.exit(1)


currentFile = __file__  # May be 'my_script', or './my_script' or
                        # '/home/user/test/my_script.py' depending on exactly how
                        # the script was run/loaded.
realPath = os.path.realpath(currentFile)  # /home/user/test/my_script.py
dirPath = os.path.dirname(realPath)  # /home/user/test
dirName = os.path.basename(dirPath) # test

if (int(sys.version_info[0]) >= 3):
    Config = ConfigParser.ConfigParser(os.environ)
    ConfigNoEnv = ConfigParser.ConfigParser(interpolation=None)
else:
    Config = ConfigParser.SafeConfigParser(os.environ)
    ConfigNoEnv = ConfigParser.SafeConfigParser(interpolation=None)
# Config._interpolation = ConfigParser.ExtendedInterpolation()
Config.read(getConfigFilePath(CONFIGFOLDER, ENVKEY))
ConfigNoEnv.read(getConfigFilePath(CONFIGFOLDER, ENVKEY))


# Get the settings
liquibasePathExe = Config.get("liquibase", "liquibasePathExe")
liquibaseDriver = Config.get("liquibase", "liquibaseDriver")
liquibaseChangeLogFile = Config.get("liquibase", "liquibaseChangeLogFile")
liquibaseAction = Config.get("liquibase", "liquibaseAction")
liquibaseActionValue = Config.get("liquibase", "liquibaseActionValue")

# {... rate_me
liquibasePropertiesFile = Config.get("liquibase", "liquibasePropertiesFile", fallback='')
liquibasePromptForDBPassword = Config.get("liquibase", "liquibasePromptForDBPassword", fallback='0')
# rate_me ...}

sqliteBin = Config.get("sqlite", "sqliteBin")
dbpath = Config.get("environment", "dbpath")
dbuser = Config.get("environment", "dbuser", fallback='')
dbpassword = Config.get("environment", "dbpassword", fallback='')
liquibaseDatabaseChangeLogTableName=Config.get("liquibase", "liquibaseDatabaseChangeLogTableName", fallback='DATABASECHANGELOG')
liquibaseDatabaseChangeLogLockTableName=Config.get("liquibase", "liquibaseDatabaseChangeLogLockTableName", fallback='DATABASECHANGELOGLOCK')

liquibaseDriverUrlprefix = Config.get("environment", "liquibaseDriverUrlprefix")
comment = Config.get("other", "comment")

logfilepath = os.path.join(os.path.dirname(getFilePathRelativeScriptPath(liquibaseChangeLogFile)),"logs")
if not os.path.exists(logfilepath) & os.path.isdir(logfilepath):
	try:
		os.mkdir(logfilepath)
	except OSError:
		print ("Creation of the directory %s failed" % logfilepath)
	else:
		print ("Successfully created the directory: %s" % logfilepath)

# Dump settings
printCurrentEnvSettings("Script path", realPath)
printCurrentEnvSettings("liquibasePathExe", liquibasePathExe)
printCurrentEnvSettings("liquibaseDriver", liquibaseDriver)
printCurrentEnvSettings("liquibaseChangeLogFile", liquibaseChangeLogFile)
printCurrentEnvSettings("Absolute liquibaseChangeLogFile", getFilePathRelativeScriptPath(liquibaseChangeLogFile))
printCurrentEnvSettings("liquibaseAction", liquibaseAction)
printCurrentEnvSettings("liquibaseActionValue", liquibaseActionValue)

# {... rate_me
printCurrentEnvSettings("liquibasePropertiesFile", liquibasePropertiesFile)
printCurrentEnvSettings("liquibasePromptForDBPassword", liquibasePromptForDBPassword)
printCurrentEnvSettings("Absolute liquibasePropertiesFile", getFilePathRelativeScriptPath(liquibasePropertiesFile))
# rate_me ...}

printCurrentEnvSettings("sqliteBin", sqliteBin)
printCurrentEnvSettings("dbpath", dbpath)
printCurrentEnvSettings("dbuser", dbuser)
# printCurrentEnvSettings("dbpassword", dbpassword)
printCurrentEnvSettings("liquibaseDatabaseChangeLogTableName", liquibaseDatabaseChangeLogTableName)
printCurrentEnvSettings("liquibaseDatabaseChangeLogLockTableName", liquibaseDatabaseChangeLogLockTableName)
printCurrentEnvSettings("Absolute dbpath", getFilePathRelativeScriptPath(dbpath))
printCurrentEnvSettings("liquibaseDriverUrlprefix", liquibaseDriverUrlprefix)
printCurrentEnvSettings("comment", comment)


if liquibaseDriver == "org.sqlite.JDBC":
    if not (os.path.isfile(getFilePathRelativeScriptPath(dbpath))) and not (os.path.islink(getFilePathRelativeScriptPath(dbpath))):
        raise Exception("SQLite File not found!")
        sys.exit(1)

# {... rate_me
if liquibasePromptForDBPassword == "1":
    from getpass import getpass
    dbpassword = getpass()
# rate_me ...}



# Liquibase parameter
liquibase_parameter=[]
liquibase_parameter.append(liquibasePathExe)
liquibase_parameter.append("--driver=" + liquibaseDriver)
liquibase_parameter.append("--databaseChangeLogTableName=" + liquibaseDatabaseChangeLogTableName)
liquibase_parameter.append("--databaseChangeLogLockTableName=" + liquibaseDatabaseChangeLogLockTableName)
liquibase_parameter.append("--changeLogFile=" + getFilePathRelativeScriptPath(liquibaseChangeLogFile))
liquibase_parameter.append("--url=" + liquibaseDriverUrlprefix)
# handling for databases with user and password
if (dbuser != ""):
    liquibase_parameter.append("--username=" + dbuser)
if (dbpassword != ""):
    liquibase_parameter.append("--password=" + dbpassword)

# {... rate_me
if (liquibasePropertiesFile != ""):
    liquibase_parameter.append("--defaultsFile=" + getFilePathRelativeScriptPath(liquibasePropertiesFile))
# rate_me ...}

if DEBUG:
    liquibase_parameter.append("--logLevel=" + "FINE")
liquibase_parameter.append(liquibaseAction)

if liquibaseActionValue != "":
    liquibase_parameter.append(liquibaseActionValue)

addi_params_found, addi_params = getAdditionalLiquibaseParameter(Config=ConfigNoEnv)
if addi_params_found:
    for parameter_key,parameter_value in addi_params.items():
        liquibase_parameter.append(f"-D{parameter_key}={parameter_value}")

# exec liquibase command
try:
    try:
        output = subprocess.check_output(liquibase_parameter, stderr=subprocess.STDOUT).decode()
    except:
        output = subprocess.check_output(liquibase_parameter, stderr=subprocess.STDOUT)     
    success = True
except subprocess.CalledProcessError as e:
    output = ""
    try:
        output = e.output.decode()
    except:
        output = e.output
    success = False
writeOutputLog(logfilepath, output)
print(output)

if DEBUG:
    print(f"""LiquiBase command:
    {" ".join(liquibase_parameter)}
    """)

    print(f"""All config items from section 'additional_liquibase_parameter':
    Parameter found: {addi_params_found}
    Parameters: {addi_params}""")

if "Cannot find database driver: org.postgresql.Driver" in output:
    from urllib.request import urlretrieve
    jdbcdriver="postgresql-42.7.1.jar"
    url = "https://jdbc.postgresql.org/download/" + jdbcdriver
    download_path_temp = liquibasePathExe.split(os.sep)
    download_path_temp[-1]='lib'
    download_path_temp.append(jdbcdriver)
    download_path=os.sep.join(download_path_temp)
    urlretrieve(url, download_path)
    print(f"Driver downloaded from {url} to {download_path}. Please try again." )
    
if not success:
    sys.exit(-1)
else:
    sys.exit(0)