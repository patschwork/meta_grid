#!/usr/bin/python
# -*- coding: utf-8 -*-
# deploy_linux.py 
# deploy liquibase changelogs
# on OS=linux
import os
import sys
import ConfigParser
import subprocess
import datetime

CONFIGFOLDER = ""
DATETIMENOW = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")

# Command Line Parameters will be detected here
def cli_params():
    global ENVKEY
    global CONFIGFOLDER
    print sys.argv
    if len(sys.argv) < 2:
        print "You must call me like:"
        print "   deploy.py <Environment Key (e.g. DEV)>"
        raise Exception("Call parameter missing!")
        sys.exit(1)
    ENVKEY = sys.argv[1]
    try:
        CONFIGFOLDER = sys.argv[2]
    except:
        print "CONFIGFOLDER not given. Using default"

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
    print prop + ": "  + value
    writeOutputLog(logfilepath, prop + ": "  + value + "\n")
	
def getFilePathRelativeScriptPath(filepath):
    return os.path.abspath(os.path.join(dirPath, filepath))

debug=0
cli_params()

if CONFIGFOLDER == "":
    CONFIGFOLDER = "D:\\Entwicklung\\_Privat\\meta_grid\\DWH_Meta_wrkCpy_DEV\\dwh_meta_v2\\database_model\\liquibase\\"


if (os.path.isfile(getConfigFilePath(CONFIGFOLDER, ENVKEY))):
    print "Config file found: " + getConfigFilePath(CONFIGFOLDER, ENVKEY)
else:
    raise Exception("Config file not found!")
    sys.exit(1)


currentFile = __file__  # May be 'my_script', or './my_script' or
                        # '/home/user/test/my_script.py' depending on exactly how
                        # the script was run/loaded.
realPath = os.path.realpath(currentFile)  # /home/user/test/my_script.py
dirPath = os.path.dirname(realPath)  # /home/user/test
dirName = os.path.basename(dirPath) # test


Config = ConfigParser.SafeConfigParser()
# Config._interpolation = ConfigParser.ExtendedInterpolation()
Config.read(getConfigFilePath(CONFIGFOLDER, ENVKEY))

# Get the settings
liquibasePathExe = Config.get("liquibase", "liquibasePathExe")
liquibaseDriver = Config.get("liquibase", "liquibaseDriver")
liquibaseChangeLogFile = Config.get("liquibase", "liquibaseChangeLogFile")
liquibaseAction = Config.get("liquibase", "liquibaseAction")
liquibaseActionValue = Config.get("liquibase", "liquibaseActionValue")
sqliteBin = Config.get("sqlite", "sqliteBin")
dbpath = Config.get("environment", "dbpath")
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
printCurrentEnvSettings("sqliteBin", sqliteBin)
printCurrentEnvSettings("dbpath", dbpath)
printCurrentEnvSettings("Absolute dbpath", getFilePathRelativeScriptPath(dbpath))
printCurrentEnvSettings("liquibaseDriverUrlprefix", liquibaseDriverUrlprefix)
printCurrentEnvSettings("comment", comment)


if liquibaseDriver == "org.sqlite.JDBC":
    if not (os.path.isfile(getFilePathRelativeScriptPath(dbpath))) and not (os.path.islink(getFilePathRelativeScriptPath(dbpath))):
        raise Exception("SQLite File not found!")
        sys.exit(1)

# exec liquibase
if liquibaseActionValue != "":
    #subprocess.call([liquibasePathExe, "--driver=" + liquibaseDriver, "--changeLogFile=" + getFilePathRelativeScriptPath(liquibaseChangeLogFile), "--url=" + liquibaseDriverUrlprefix , liquibaseAction, liquibaseActionValue])
	try:
		output = subprocess.check_output([liquibasePathExe, "--driver=" + liquibaseDriver, "--changeLogFile=" + getFilePathRelativeScriptPath(liquibaseChangeLogFile), "--url=" + liquibaseDriverUrlprefix , liquibaseAction, liquibaseActionValue], stderr=subprocess.STDOUT).decode()
		success = True 
	except subprocess.CalledProcessError as e:
		output = e.output.decode()
		success = False
	writeOutputLog(logfilepath, output)
	print output
else:
    #subprocess.call([liquibasePathExe, "--driver=" + liquibaseDriver, "--changeLogFile=" + getFilePathRelativeScriptPath(liquibaseChangeLogFile), "--url=" + liquibaseDriverUrlprefix , liquibaseAction])        
	try:
		output = subprocess.check_output([liquibasePathExe, "--driver=" + liquibaseDriver, "--changeLogFile=" + getFilePathRelativeScriptPath(liquibaseChangeLogFile), "--url=" + liquibaseDriverUrlprefix , liquibaseAction], stderr=subprocess.STDOUT).decode()
		success = True 
	except subprocess.CalledProcessError as e:
		output = e.output.decode()
		success = False
	writeOutputLog(logfilepath, output)
	print output
