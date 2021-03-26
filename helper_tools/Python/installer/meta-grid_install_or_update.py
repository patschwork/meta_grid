#!/usr/bin/python
# -*- coding: utf-8 -*-
# Instllation or update meta#grid
# on every OS
# v1.2

import base_lib
import shutil
import os
import datetime

debug=0
simulate_alternate_folder=False

## ###################################################

const_sqlite = "sqlite"
const_inst_mode_installation = "inst"
const_inst_mode_update = "upd"


behaviour = const_inst_mode_update
git_repo_url = "https://github.com/patschwork/meta_grid.git"
git_repo_zip_url = "https://github.com/patschwork/meta_grid/archive/master.zip"
used_rdbms = const_sqlite
folderfile_Database = "../../../dwh_meta.sqlite"
folder_Frontend = "../../../frontend/yii/basic"
folder_Bulkimport = "../../../bulk_import/kettle"
liquibasePathExe = "/opt/meta_grid/tools/liquibase/liquibase"
kitchenPathExe = "/opt/meta_grid/tools/pdi/kitchen.sh"
pythonExe = "python"
kettleMajorJobFilename = "run_import.kjb"
metagridMajorFrontendFilename = "gii_crud.sh"
if (base_lib.is_windows()):
    liquibasePathExe = os.path.join("C:\\", "meta_grid", "tools", "liquibase", "liquibase.bat")
    kitchenPathExe = os.path.join("C:\\", "meta_grid", "tools", "pdi", "kitchen.bat")
    pythonExe = os.path.join("C:\\", "python27", "python.exe")
    metagridMajorFrontendFilename = "gii_crud.bat" # yii.bat finds to many entries

## ###################################################

param_make_screen_clear = True
param_remove_folder_WorkingDir = True
param_remove_folder_FreshRepo = False
param_checkGitInstalled = False
param_makeClone = True
param_download_repo_zip = True
param_extract_repo_zip = True
param_create_db_backup = True
param_create_frontend_backup = True
param_create_bulkimport_backup = True

if (param_download_repo_zip):
    param_makeClone=False
    param_checkGitInstalled=False

if (param_remove_folder_WorkingDir):
    param_remove_folder_FreshRepo=False

## ###################################################

def bla(msg, action=None, withLooging=True, wait=False):
    base_lib.bla(msg, action, withLooging, logfilepath, logfile)
    if (wait):
        dummy = raw_input("")

if (param_make_screen_clear):
    base_lib.clear()

currentFile = __file__  # May be 'my_script', or './my_script' or
                        # '/home/user/test/my_script.py' depending on exactly how
                        # the script was run/loaded.
realPath = os.path.realpath(currentFile)  # /home/user/test/my_script.py
dirPath = os.path.dirname(realPath)  # /home/user/test
dirName = os.path.basename(dirPath) # test
DATETIMENOW = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
logfile = "log_" + DATETIMENOW + ".txt"

logfilepath = os.path.join(dirPath,"logs")
ini_user_settings_path = os.path.join(dirPath,"install_settings.ini")

bla("meta#grid Installation/Update (v" + base_lib.myVersion() + ")", "intro", False)
bla("--------------------------------------", "intro", False)
bla("(currently only Update possible)", "intro", False)
print("\r\n")

version_check = base_lib.check_if_a_newer_version_is_available()
if version_check:
    bla("A new version of the installer programm is available: " + format(str(base_lib.check_if_a_newer_version_is_available())), "new_version", True, False)
    bla(r'Please cancel the actual process and use a new copy from: https://github.com/patschwork/meta_grid_install_update', "new_version", True, True)
    print("\r\n")

bla("- Check if filesystem is writeable by the process", "header", False)
if (base_lib.check_file_writable("dummyFolder") == False):
    errMsg="Can't write to the filesystem! Abort."
    bla(errMsg, "error", False, True)
    raise Exception(errMsg)
    exit()





# ###################### BEGIN -> Get inputs from user {...

base_lib.intro_get_input_color()

# not yet choosable, but...
dummy=base_lib.get_input_color("[I]nstallation or [U]pdate", "Update", "U", no_input_just_inform_user=True)

section="meta_grid_source"
if (param_download_repo_zip):
    actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'git_repo_zip_url', git_repo_zip_url)
    git_repo_zip_url=base_lib.get_input_color("URL for GitHub zip release file", actual_value, git_repo_zip_url)
    base_lib.set_user_settings(ini_user_settings_path, section, 'git_repo_zip_url', git_repo_zip_url)
else:
    actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'git_repo_url', git_repo_url)
    git_repo_url=base_lib.get_input_color("URL for GitHub meta#grid repository", actual_value, git_repo_url)
    base_lib.set_user_settings(ini_user_settings_path, section, 'git_repo_url', git_repo_url)

if (used_rdbms == const_sqlite):
    section="current_installation"
    actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'folderfile_Database', folderfile_Database)
    folderfile_Database=base_lib.get_input_color("File location for the SQLite database file", actual_value, user_option_search_file=os.path.basename(folderfile_Database))
    base_lib.set_user_settings(ini_user_settings_path, section, 'folderfile_Database', folderfile_Database)

section="current_installation"
actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'folder_Frontend', folder_Frontend)
folder_Frontend=base_lib.get_input_color("Folder location for the frontend files", actual_value, folder_Frontend, user_option_search_file=metagridMajorFrontendFilename, use_path_of_search_result=True)
base_lib.set_user_settings(ini_user_settings_path, section, 'folder_Frontend', folder_Frontend)

actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'folder_Bulkimport', folder_Bulkimport)
folder_Bulkimport=base_lib.get_input_color("Folder location for the bulkimport files", actual_value, folder_Bulkimport, user_option_search_file=kettleMajorJobFilename, use_path_of_search_result=True)
base_lib.set_user_settings(ini_user_settings_path, section, 'folder_Bulkimport', folder_Bulkimport)

section="tools"
actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'liquibasePathExe', liquibasePathExe)
liquibasePathExe=base_lib.get_input_color("Path to LiquiBase installation", actual_value, liquibasePathExe, user_option_search_file=os.path.basename(liquibasePathExe))
base_lib.set_user_settings(ini_user_settings_path, section, 'liquibasePathExe', liquibasePathExe)

section="tools"
actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'pythonExe', pythonExe)
pythonExe=base_lib.get_input_color("Path to Python executable", actual_value, pythonExe)
base_lib.set_user_settings(ini_user_settings_path, section, 'pythonExe', pythonExe)

section="tools"
actual_value=base_lib.get_user_settings(ini_user_settings_path, section, 'kitchenPathExe', kitchenPathExe)
kitchenPathExe=base_lib.get_input_color("Path to Pentaho Data Integration (kitchen.sh) executable", actual_value, user_option_search_file=os.path.basename(kitchenPathExe))
base_lib.set_user_settings(ini_user_settings_path, section, 'kitchenPathExe', kitchenPathExe)

# ###################### END -> Get inputs from user ...}


# from IPython import embed; embed() # jump into IPython console...

bla("- Create folder for log messages if not exits", "header", False)
returnVal = base_lib.createDirIfNotExists(logfilepath)
if (returnVal != None):
    bla(returnVal.split("||")[1], returnVal.split("||")[0], True)

# Check if kitchen is found
try:
    if (not os.path.exists(kitchenPathExe)):
        raise Exception("Pentaho Data Integration executable (" + kitchenPathExe + ") not found! Installatation/Update not completed!")
except Exception as e:
    bla(str(e), "error", True, True)
    exit()

# set variable runImportPathFile (e. g. /opt/meta_grid/bulk_loader/run_import.kjb)
runImportPathFile = os.path.join(folder_Bulkimport, kettleMajorJobFilename)

# only if update, create an inital parameter list (for comparing later)
if (behaviour==const_inst_mode_update):
    try:
        if (not os.path.exists(runImportPathFile)):
            raise Exception("Main Job-File of the meta#grid bulk loader (" + runImportPathFile + ") not found! Installatation/Update not completed!")
    except Exception as e:
        bla(str(e), "error", True, True)
        exit()

    bla("- Extract current list of parameter from bulk loader", "header", False)
    #paramKitchenFile = '-file:' + runImportPathFile
    #kettleJobParameterList = base_lib.get_kettle_job_parameter(kitchenPathExe, paramKitchenFile)
    kettleJobParameterList = base_lib.get_kettle_job_parameter_v2(runImportPathFile)

# The installer is located in <mg_Inst_Dir>/updating

# operations needed to installer are done in <folder_WorkingDir>. This folder can savely be removed after the installation/update
folder_WorkingDir = "_working_dir"

folder_FreshRepo = os.path.join(folder_WorkingDir, "fresh_repo")
folder_Backup = "backup"
folderfile_downloaded_zip_file=os.path.join(folder_WorkingDir, 'meta_grid-master.zip')
if (param_download_repo_zip):
    folder_FreshRepo = os.path.join(folder_WorkingDir, "meta_grid-master")
    base_lib.writeOutputLog(logfilepath, logfile, "Changed value of variable 'folder_FreshRepo' to '" + folder_FreshRepo + "'")
# the new frontend file are located in <folder_FreshRepo>/meta_grid
folder_Fresh_frontend = os.path.join(folder_FreshRepo, "meta_grid")
folder_Fresh_bulkimport = os.path.join(folder_FreshRepo, "bulk_import", "kettle")
folderfile_Fresh_LQB_Changelog = os.path.join(folder_FreshRepo, "database_model", "liquibase", "db.changelog-master.xml")
folderfile_Fresh_LQB_DeploymentTool = os.path.join(folder_FreshRepo, "helper_tools", "Python", "liquibase", "deploy.py")

if (param_checkGitInstalled):
    bla("- Check if Git is installed/accessable", "header", False)
    if (base_lib.checkIfGitIsInstalled() == False):
        errMsg="Git is not installed! Abort."
        bla(errMsg, "error", True, True)
        raise Exception(errMsg)
        exit()
else:
    bla("- param_checkGitInstalled=False", "noExecutionBecauseParamFalse", True)

if (param_remove_folder_WorkingDir):
    bla("- Removing working dir", "header", False)
    shutil.rmtree(folder_WorkingDir, ignore_errors=True)
else:
    bla("- param_remove_folder_WorkingDir=False", "noExecutionBecauseParamFalse", True)

# Create working_dir folder
bla("- Create folder for working items if not exits", "header", False)
returnVal = base_lib.createDirIfNotExists(folder_WorkingDir)
if (returnVal != None):
    bla(returnVal.split("||")[1], returnVal.split("||")[0], True)

# Check delete folder <mg_Inst_Dir>/updating/<folder_WorkingDir>/fresh_repo
if (param_remove_folder_FreshRepo):
    bla("- Removing fresh_repo dir", "header", False)
    shutil.rmtree(folder_FreshRepo, ignore_errors=True)
else:
    bla("- param_remove_folder_FreshRepo=False", "noExecutionBecauseParamFalse", True)

# Clone
if (param_makeClone):
    bla("- Clone Git repository", "header", False)
    res=base_lib.gitCloneToPath(git_repo_url, folder_FreshRepo)
    bla("Successful: Git clone", "OK", True)
    #base_lib.writeOutputLog(logfilepath, logfile, res)
else:
    bla("- param_makeClone=False", "noExecutionBecauseParamFalse", True)

# Download zip from GitHub
if (param_download_repo_zip):
    bla("- Download release zip from GitHub", "header", False)
    res=base_lib.download_file(git_repo_zip_url, folderfile_downloaded_zip_file)
    if (res):
        bla("Successful: Download zip file", "OK", True)
    else:
        bla("Error on download zip file", "NOK", True, True)
else:
    bla("- param_download_repo_zip=False", "noExecutionBecauseParamFalse", True)

# Extract zip
if (param_extract_repo_zip):
    bla("- Extract release zip file", "header", False)
    res=base_lib.extract_zip(folderfile_downloaded_zip_file, folder_WorkingDir)
    if (res):
        bla("Successful: Extracted zip file", "OK", True)
    else:
        errMsg="Error on extracting zip file! Installatation/Update not completed!"
        bla(errMsg, "NOK", True, True)
        raise Exception(errMsg)
        exit()
else:
    bla("- param_extract_repo_zip=False", "noExecutionBecauseParamFalse", True)

# Create backup folder
bla("- Create folder for backups if not exits", "header", False)
returnVal = base_lib.createDirIfNotExists(folder_Backup)
if (returnVal != None):
    bla(returnVal.split("||")[1], returnVal.split("||")[0], True)

# Make a backup of the current database file if sqlite
if (used_rdbms == const_sqlite):
    if (param_create_db_backup):
        try:
            bla("- Create backup of SQLite database file", "header", False)
            abspath_db = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folderfile_Database), os.path.basename(folderfile_Database))
            zipfilename_db = os.path.join(folder_Backup, DATETIMENOW + "_sqlite_db.zip")
            base_lib.makeZipFromFile(abspath_db, zipfilename_db)
            bla("Successful: Created a zip file (" + base_lib.getFilePathRelativeScriptPath(folder_Backup, os.path.basename(zipfilename_db)) + ") from database file (" + abspath_db + ")", "OK", True)
        except Exception as e:
            errMsg="Error on creating zip file! Abort."
            bla(str(e), "error", True)
            bla(errMsg, "error", True, True)
            raise Exception(errMsg)
            exit()           
    else:
        bla("- param_create_db_backup=False", "noExecutionBecauseParamFalse", True)   

# simulate to an alternate folder
if (simulate_alternate_folder):
    folder_Frontend = "new_frontend"

# Make a backup of the current frontend files (Yii2)
if (param_create_frontend_backup):
    try:
        bla("- Create backup of frontend files", "header", False)
        abspath_frontend = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folder_Frontend), os.path.basename(folder_Frontend))
        zipfilename_frontend = os.path.join(folder_Backup, DATETIMENOW + "_frontend.zip")
        base_lib.makeZipFromFolder(abspath_frontend, zipfilename_frontend)
        bla("Successful: Created a zip file (" + base_lib.getFilePathRelativeScriptPath(folder_Backup, os.path.basename(zipfilename_frontend)) + ") from frontend folder (" + abspath_frontend + ")", "OK", True)
    except Exception as e:
        errMsg="Error on creating zip file! Installatation/Update not completed!"
        bla(str(e), "error", True)
        bla(errMsg, "error", True, True)
        raise Exception(errMsg)
        exit()  
else:
    bla("- param_create_frontend_backup=False", "noExecutionBecauseParamFalse", True)   

# Make a backup of the current bulkimport files (Kettle)
if (param_create_bulkimport_backup):
    try:
        bla("- Create backup of bulkimport files", "header", False)
        abspath_bulkimport = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folder_Bulkimport), os.path.basename(folder_Bulkimport))
        zipfilename_bulkimport = os.path.join(folder_Backup, DATETIMENOW + "_bulkimport.zip")
        base_lib.makeZipFromFolder(abspath_bulkimport, zipfilename_bulkimport)
        bla("Successful: Created a zip file (" + base_lib.getFilePathRelativeScriptPath(folder_Backup, os.path.basename(zipfilename_bulkimport)) + ") from bulkimport folder (" + abspath_bulkimport + ")", "OK", True)
    except Exception as e:
        errMsg="Error on creating zip file! Installatation/Update not completed!"
        bla(str(e), "error", True)
        bla(errMsg, "error", True, True)
        raise Exception(errMsg)
        exit()  
else:
    bla("- param_create_bulkimport_backup=False", "noExecutionBecauseParamFalse", True)   

# Copy the new frontend files
# Create a list of items to be copied
bla("- Create list of files (copylist) to be copied for the frontend", "header", False)
copylist = os.listdir(folder_Fresh_frontend)
# remove elements which mustn't be copied (if this is the first install, then this should be copied as well)
if (behaviour==const_inst_mode_update):
    bla("- - Do not copy config folder (update mode) - removed from the copylist", "subheader", False)
    copylist.remove('config')

base_lib.writeOutputLog(logfilepath, logfile, "Frontend copylist: " + "\r\n" + "---------------------------------------------")
base_lib.writeOutputLog(logfilepath, logfile, "\r\n" + base_lib.listToString(copylist) + "---------------------------------------------")

bla("- Copy frontend files", "header", False)
try:
    for element in copylist:   
        abspath_frontend = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folder_Frontend), os.path.basename(folder_Frontend)) 
        base_lib.copyFilesAndFolder(os.path.join(folder_Fresh_frontend, element), abspath_frontend)
    bla("Successful: Frontend files copied", "OK", True)
except Exception as e:
    errMsg="Error on copying files! You should consider to restore a backup (see zip-file above). Installatation/Update not completed!"
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    raise Exception(errMsg)
    exit()

# Copy the new bulkimport files
# Create a list of items to be copied
bla("- Create list of files (copylist) to be copied for the bulkimport", "header", False)
copylist = os.listdir(folder_Fresh_bulkimport)

base_lib.writeOutputLog(logfilepath, logfile, "Bulkimport copylist: " + "\r\n" + "---------------------------------------------")
base_lib.writeOutputLog(logfilepath, logfile, "\r\n" + base_lib.listToString(copylist) + "---------------------------------------------")

bla("- Copy bulkimport files", "header", False)
try:
    for element in copylist:   
        abspath_bulkimport = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folder_Bulkimport), os.path.basename(folder_Bulkimport)) 
        base_lib.copyFilesAndFolder(os.path.join(folder_Fresh_bulkimport, element), abspath_bulkimport)
    bla("Successful: Bulkimport files copied", "OK", True)
except Exception as e:
    errMsg="Error on copying files! You should consider to restore a backup (see zip-file above). Installatation/Update not completed!"
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    raise Exception(errMsg)

# Clean up <folder_Frontend>/web/assets
folder_Frontend_web_assets = os.path.join(folder_Frontend, "web", "assets")
bla("- Cleanup folder " + folder_Frontend_web_assets, "header", False)
# Only clean up if exits
if os.path.exists(folder_Frontend_web_assets) & os.path.isdir(folder_Frontend_web_assets):
    try:
        base_lib.cleanupFolderInside(folder_Frontend_web_assets)
        bla("Successful: Cleanup folder web/assets", "OK", True)
    except Exception as e:
        errMsg="Error on cleanup folder web/assets. You should consider to clean up the folder (" + folder_Frontend_web_assets + ") manually."
        bla(str(e), "error", True)
        bla(errMsg, "error", True, True)

# Prepare LiquiBase deployment
bla("- Create config file for LiquiBase database deployment", "header", False)
dynConfigIni = os.path.join(folder_WorkingDir,"deploy_config_LQB_" + DATETIMENOW + ".ini")
abs_folderfile_Fresh_LQB_Changelog = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folderfile_Fresh_LQB_Changelog), os.path.basename(folderfile_Fresh_LQB_Changelog))

# simulate to an alternate folder
if (simulate_alternate_folder):
    folderfile_Database="old_db/dwh_meta_demo.sqlite"

abspath_db = base_lib.getFilePathRelativeScriptPath(os.path.dirname(folderfile_Database), os.path.basename(folderfile_Database))
try:
    base_lib.createLQBConfigDeployIniFile(cfgFile=dynConfigIni, liquibasePathExe=liquibasePathExe, liquibaseChangeLogFile=abs_folderfile_Fresh_LQB_Changelog, dbpath=abspath_db)
    bla("Successful: Created config file for LiquiBase deployment (" + dynConfigIni + ")", "OK", True)
except Exception as e:
    errMsg="Error on creating a deployment config file for LiquiBase deployment! LiquiBase deployment will not work correctly. Installatation/Update not completed!"
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    exit()

# exec LiquiBase Deployment script (also in Python)
bla("- Start LiquiBase database deployment", "header", False)
try:
    if (not os.path.exists(liquibasePathExe)):
        raise Exception("LiquiBase executable (" + liquibasePathExe + ") not found! Installatation/Update not completed!")
except Exception as e:
    bla(str(e), "error", True, True)
    exit()

try:
    os.environ["JAVA_OPTS"] = "-Duser.language=en"
    returnVal=base_lib.LQB_exec(pythonExe=pythonExe, folderfile_Fresh_LQB_DeploymentTool=folderfile_Fresh_LQB_DeploymentTool, envkey="LQB_" + DATETIMENOW, configpath=base_lib.getFilePathRelativeScriptPath(folder_WorkingDir,''))
    if ("Liquibase Update Successful" in returnVal):
        bla(returnVal, "OK", True)
    elif ("Update has been successful" in returnVal):
        bla(returnVal, "OK", True)
    else:
        bla("The LiquiBase deployment did not quit with a successful comment...: " + returnVal, "NOK", True, True)
except Exception as e:
    errMsg="Error occured on database deployment! Please check the logs of the LiquiBase deployment helper (" + os.path.dirname(folderfile_Fresh_LQB_DeploymentTool) + ")."
    bla(str(e), "error", True)
    bla(errMsg, "error", True, True)
    raise Exception(errMsg)

# Inform the user about deltas (added, removed) parameter for the bulk loader
if (behaviour==const_inst_mode_update):
    try:
        if (not os.path.exists(runImportPathFile)):
            raise Exception("Main Job-File of the meta#grid bulk loader (" + runImportPathFile + ") not found! Installatation/Update not completed!")
    except Exception as e:
        bla(str(e), "error", True, True)
        exit()

    bla("- Extract new list of parameter from bulk loader", "header", False)
    # paramKitchenFile = '-file:' + runImportPathFile
    # kettleJobParameterList_new = base_lib.get_kettle_job_parameter(kitchenPathExe, paramKitchenFile)
    kettleJobParameterList_new = base_lib.get_kettle_job_parameter_v2(runImportPathFile)
    merged = base_lib.merge_kettle_parameter_old_new(kettleJobParameterList, kettleJobParameterList_new)
    deltas = base_lib.qualify_kettle_paramter_to_new_old(merged, kettleJobParameterList, kettleJobParameterList_new)
    changes_detected = False
    for key in deltas:
        if (deltas[key] == "+"):
            changes_detected = True
            bla(msg="New parameter: " + key, action="OK", withLooging=True)
        if (deltas[key] == "-"):
            changes_detected = True
            bla(msg="Removed parameter: " + key, action="NOK", withLooging=True)
    if (changes_detected):
        bla(msg="Please check your bulkloader scripts and add/remove the parameter", action="new_version", withLooging=True, wait=True)
        bla(msg="List of parameter:", withLooging=True, wait=False)
        for key in kettleJobParameterList_new:
            msg = key
            if "description" in kettleJobParameterList_new[key]:
                msg = key + ": " + kettleJobParameterList_new[key]["description"]
            if "default_value" in kettleJobParameterList_new[key]:
                msg = key + " (Default value: " + kettleJobParameterList_new[key]["default_value"] + ")"
            bla(msg=msg, withLooging=True, wait=False)

bla("# End of process #", "endOfScript", True)
if (base_lib.is_windows()):
    dummy = raw_input()