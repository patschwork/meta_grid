#!/usr/bin/python
# -*- coding: utf-8 -*-
# Instllation or update meta#grid
# on every OS
# Base helper library
# v1.8

import os
import subprocess
import zipfile
import shutil
import errno
import sys
from distutils_local.dir_util import copy_tree
if (int(sys.version_info[0]) >= 3):
    import configparser as ConfigParser
else:
    import ConfigParser
from termcolor import colored
from termcolor import cprint
import datetime
if (int(sys.version_info[0]) >= 3):
    from urllib.request import urlopen
else:
    from urllib2 import urlopen
import colorama
# from lastversion import lastversion
import json
from packaging import version
import string
import re
import time
from xml.dom import minidom
import ctypes

# That gives the version of the Installer/Updater
def myVersion():
    return "1.8"

# Print messages in different color (with, or without logging)
def bla(msg, action=None, withLooging=True, logfilepath="", logfile=""):
    colorama.init()
    if (action=="intro"):
        cprint(msg, 'white', attrs=['bold'])
    elif (action=="header"):
        cprint(msg, 'white', 'on_blue')
    elif (action=="new_version"):
        cprint(msg, 'red', 'on_grey')
    elif (action=="subheader"):
        color='blue'
        if ((is_windows())):
            color='cyan'
        cprint(msg, color)
    elif (action=="error"):
        cprint(msg, 'white', 'on_red')
    elif (action=="OK"):
        cprint(msg, 'green')
    elif (action=="NOK"):
        cprint(msg, 'red')    
    elif (action=="noExecutionBecauseParamFalse"):
        cprint(msg, 'white', 'on_grey')
    elif (action=="warning"):
        cprint(msg, color='yellow', on_color=None, attrs=['bold'])
    elif (action=="endOfScript"):
        color='grey'
        if ((is_windows())):
            color='green'
        cprint(msg, color, 'on_yellow', attrs=['bold'])
    else:
        print(msg)
    
    if (withLooging) & (logfilepath != "") & (logfile != "") & (msg != None):
        writeOutputLog(logfilepath, logfile, msg)

# Credits to: https://www.novixys.com/blog/python-check-file-can-read-write/
def check_filefolder_writable(fnm, checkfolder=False):
    if os.path.exists(fnm):
        # path exists
        if os.path.isfile(fnm): # is it a file or a dir?
            # also works when file is a link and the target is writable
            return os.access(fnm, os.W_OK)
        else:
            if not checkfolder:
                return False # path is a dir, so cannot write as a file
    # target does not exist, check perms on parent dir
    pdir = os.path.dirname(fnm)
    if not pdir: pdir = '.'
    # target is creatable if parent dir is writable
    return os.access(pdir, os.W_OK)

def getFilePathRelativeScriptPath(dirPath, filepath):
    return os.path.abspath(os.path.join(dirPath, filepath))

def writeOutputLog(logpath, logfile, msg, timestamp=True):
    if (timestamp):
        msg = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S") + ": " + msg
    filename = os.path.join(logpath, logfile)
    myFile = open(filename,"a")
    myFile.write(msg + "\r\n")
    myFile.close()

# Check, if a tool is found/executable. Returns True if executable, and False if not
def checkIfToolIsExecutable(exec):
    returnValue=False
    try:
        # pipe output to /dev/null for silence
        null = open(os.devnull, "w")
        subprocess.Popen(exec, stdout=null, stderr=null)
        null.close()
        returnValue=True    
    except OSError:
        returnValue=False
    return returnValue

def gitCloneToPath(repo_url, path_to_clone):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen(["git", "clone", repo_url, path_to_clone], stdout=subprocess.PIPE, text=True)
    else:
        p = subprocess.Popen(["git", "clone", repo_url, path_to_clone], stdout=subprocess.PIPE)
    # return p.communicate()[0]
    out=p.communicate()[0]
    return out

def createDirIfNotExists(foldername):
    if not os.path.exists(foldername) & os.path.isdir(foldername):
        try:
            os.mkdir(foldername)
            str_list=[]
            str_list.append("OK")
            str_list.append("||")
            str_list.append("Successfully created the directory: ")
            str_list.append(foldername)
            return ''.join(str_list)
        except OSError:
            str_list=[]
            str_list.append("NOK")
            str_list.append("||")
            str_list.append("Creation of the directory ")
            str_list.append(foldername)
            str_list.append("failed")
            return ''.join(str_list)
    else:
        return None

def makeZipFromFile(fileToZip, archiveFile):
    zip = zipfile.ZipFile(archiveFile, mode='w', compression=zipfile.ZIP_DEFLATED)
    zip.write(fileToZip)
    zip.close()

def makeZipFromFolder(folderToZip, archiveFile):
    shutil.make_archive(archiveFile, 'zip', folderToZip)

# Credits to: https://www.geeksforgeeks.org/python-program-to-convert-a-list-to-string/
def listToString(list, withCrLf=True):  
    # initialize an empty string 
    str1 = ""  
    # traverse in the string   
    for ele in list:  
        str1 += ele
        if (withCrLf == True):
            str1 += "\r\n"
    # return string   
    return str1  

# Credits to: https://stackoverflow.com/questions/1994488/copy-file-or-directories-recursively-in-python
def copyFilesAndFolder(src, dst, rescueSubDirStructure=True):
    try:
        sub_dir = ""
        if (rescueSubDirStructure):
            if (os.path.isdir(src)):
                sub_dir = os.path.basename(src)
        copy_tree(src, os.path.join(dst, sub_dir))
    except:
        shutil.copy(src, dst)

def cleanupFolderInside(path):
    for root, dirs, files in os.walk(path):
        for f in files:
            os.unlink(os.path.join(root, f))
        for d in dirs:
            shutil.rmtree(os.path.join(root, d))

def createLQBConfigDeployIniFile(cfgFile, liquibasePathExe, liquibaseChangeLogFile, dbpath, liquibaseDriver="org.sqlite.JDBC", liquibaseAction="migrate", liquibaseActionValue="", sqliteBin="", liquibaseDriverUrlprefix='jdbc:sqlite:%(dbpath)s', comment="Generated"):
    if os.path.exists(cfgFile):
        os.unlink(cfgFile)
    cfgfile = open(cfgFile,'w')
    if (int(sys.version_info[0]) >= 3):
        Config = ConfigParser.ConfigParser()
    else:
        Config = ConfigParser.SafeConfigParser()
    Config.add_section('liquibase')
    Config.add_section('sqlite')
    Config.add_section('environment')
    Config.add_section('other')

    Config.set('liquibase','liquibasePathExe',liquibasePathExe)
    Config.set('liquibase','liquibaseDriver',liquibaseDriver)
    Config.set('liquibase','liquibaseChangeLogFile',liquibaseChangeLogFile)
    Config.set('liquibase','liquibaseAction',liquibaseAction)
    Config.set('liquibase','liquibaseAction',liquibaseAction)
    Config.set('liquibase','liquibaseActionValue',liquibaseActionValue)
    
    Config.set('sqlite','sqliteBin',sqliteBin)

    Config.set('environment','dbpath',dbpath)
    Config.set('environment','liquibaseDriverUrlprefix',liquibaseDriverUrlprefix)

    Config.set('other','comment',comment)
    Config.write(cfgfile)
    cfgfile.close()

def LQB_exec(pythonExe, folderfile_Fresh_LQB_DeploymentTool, envkey, configpath):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen([pythonExe, folderfile_Fresh_LQB_DeploymentTool, envkey, configpath], stdout=subprocess.PIPE, text=True)
    else:
        p = subprocess.Popen([pythonExe, folderfile_Fresh_LQB_DeploymentTool, envkey, configpath], stdout=subprocess.PIPE)
    out=p.communicate()[0]
    return out

# Credits to: https://www.geeksforgeeks.org/clear-screen-python/
# define our clear function 
def clear(): 
    # for windows 
    if os.name == 'nt': 
        _ = os.system('cls') 
    # for mac and linux(here, os.name is 'posix') 
    else: 
        _ = os.system('clear') 

def download_file_simple(url, download_to):
    try:
        filedata = urlopen(url)
        datatowrite = filedata.read()
        with open(download_to, 'wb') as f:
            f.write(datatowrite)
        if (os.path.exists(download_to)):
            return True
        else:
            raise Exception("Error on download_file. File doesn't exists.")
    except:
        return False

# Credits: https://www.alpharithms.com/progress-bars-for-python-downloads-580122/
def download_file(url, download_to):
    # Fallback if Python version below 3.7
    if ((int(sys.version_info[0]) < 3) or ((int(sys.version_info[0]) >= 3) and (int(sys.version_info[1]) < 7))):
        return download_file_simple(url, download_to)
    
    import importlib.util
    requests_spec = importlib.util.find_spec("requests")
    found = requests_spec is not None
    if found:
        import requests
    else:
        return download_file_simple(url, download_to)

    from tqdm.auto import tqdm

    try:
        # make an HTTP request within a context manager
        with requests.get(url, stream=True, allow_redirects=True) as r:
            
            # check header to get content length, in bytes
            headers = r.headers
            if 'Content-Length' in headers:
                total_length = int(r.headers.get("Content-Length"))
            else:
                total_length = 99999
            
            # implement progress bar via tqdm
            with tqdm.wrapattr(r.raw, "read", total=total_length, desc="")as raw:
            
                # save the output to a file
                # with open(f"{os.path.basename(r.url)}", 'wb')as output:
                with open(download_to, 'wb')as output:
                    shutil.copyfileobj(raw, output)
        if (os.path.exists(download_to)):
            return True
        else:
            raise Exception("Error on download_file. File doesn't exists.")
    except Exception as e:
        print(str(e))
        return False

# Credit to: https://thispointer.com/python-how-to-unzip-a-file-extract-single-multiple-or-all-files-from-a-zip-archive/
def extract_zip(zip_file, to_folder=""):
    try:
        with zipfile.ZipFile(zip_file, 'r') as zipObj:
        # Extract all the contents of zip file in current directory
            if (to_folder == ""):
                zipObj.extractall()
            else:
                zipObj.extractall(to_folder)
        res=True
    except:
        res=False
    return res

def get_input(msg, default_value=""):
    if (default_value != ""):
        msg = msg + " [" + default_value + "]: "
    else:
        msg = msg + ": "
    if (int(sys.version_info[0]) >= 3):
        r_input = input(msg)
    else:
        r_input = raw_input(msg)
    if (r_input == ""):
        r_input = default_value
    return r_input

def get_input_color(msg, default_value="", init_value="", no_input_just_inform_user=False, user_option_search_file=None, use_path_of_search_result=False):
    colorama.init()
    msg_to_print = msg
    if (default_value != ""):
        msg_to_print = msg_to_print + colored(" [" + default_value + "]", 'green') + ""
    if (init_value != "") & (init_value != default_value):
        color='blue'
        if (is_windows()):
            color='cyan'
        msg_to_print = msg_to_print + colored(" (Inital value: " + init_value + ")", color) + ""
    if (user_option_search_file is not None and not no_input_just_inform_user):
        color='red'
        msg_to_print = msg_to_print + colored(" {To search for the file: '" + user_option_search_file + "' please type 'F'}", color) + ""
    msg_to_print = msg_to_print + ": "
    if (not no_input_just_inform_user):
        if (int(sys.version_info[0]) >= 3):
            r_input = input(msg_to_print)
        else:
            r_input = raw_input(msg_to_print)
        if (r_input == ""):
            r_input = default_value
        if (r_input.upper() == "F"):
            r_input = default_value
            found_files = {}
            found_files = find_file_in_all_drives( user_option_search_file, found_files, with_index=True, debug=False )
            if (use_path_of_search_result):
                for key in found_files:
                    found_files[key] = os.path.dirname(found_files[key])
            list_to_ckeck = found_files.copy()
            list_to_ckeck["C"]="Cancel"
            list_to_ckeck["c"]="Cancel"
            for key in found_files:
                print("[" + str(key) + "]: " + found_files[key])
            print("[C]: Cancel")
            choose = ""
            release = False
            while (not release):
                if (int(sys.version_info[0]) >= 3):
                    choose = input("Choose an option: ")
                else:
                    choose = raw_input("Choose an option: ")
                if (RepresentsInt(choose)):
                    choose_int = int(choose)
                else:
                    choose_int = -1
                if (choose_int in list_to_ckeck or choose.upper() == "C"):
                    release = True
            if (choose.upper() == "C"):
                get_input_color(msg=msg, default_value=default_value, init_value=init_value, no_input_just_inform_user=no_input_just_inform_user, user_option_search_file=user_option_search_file)
                r_input = default_value
                return r_input # Fallback already Cancel was choosen
            print(colored("-> " + found_files[choose_int], 'green'))
            r_input = found_files[choose_int]
    else:
        print("{}: {}".format(msg, str(default_value)))
        r_input = default_value
    return r_input

def intro_get_input_color():
    colorama.init()
    msg = colored("Setting", None, attrs=['bold'])
    msg = msg + colored(" [" + "Last setting -> \"Type Enter\" to choose" + "]", 'green', attrs=['bold']) + ""
    color='blue'
    if (is_windows()):
        color='cyan'
    msg = msg + colored(" (Inital value: " + "This is the orginal value. Copy & Paste to use again." + ")", color, attrs=['bold']) + ""
    print(msg)

def get_user_settings(cfgFile, section, key, default_if_key_not_exists=""):
    if (int(sys.version_info[0]) >= 3):
        config = ConfigParser.ConfigParser()
    else:
        config = ConfigParser.SafeConfigParser()
    config.read(cfgFile)
    if (not config.has_section(section)):
        return default_if_key_not_exists
    if (not config.has_option(section, key)):
        return default_if_key_not_exists
    return config.get(section,key)

def set_user_settings(cfgFile, section, key, value):
    if (int(sys.version_info[0]) >= 3):
        config = ConfigParser.ConfigParser()
    else:
        config = ConfigParser.SafeConfigParser()
    config.read(cfgFile)
    if (not config.has_section(section)):
        config.add_section(section)
    config.set(section,key,value)
    with open(cfgFile, 'w') as configfile:
        config.write(configfile)

def is_windows(): 
    if os.name == 'nt':
        return True
    else:
        return False

def check_if_a_newer_version_is_available():
    # lastversion has to many dependencies :-(
    # latest_version = lastversion.has_update(repo="https://github.com/patschwork/meta_grid_install_update", current_version='1.2')
    # return latest_version
    url = "http://httpbin.org/get"
    response = urlopen("https://api.github.com/repos/patschwork/meta_grid_install_update/releases/latest")
    data = response.read()
    values = json.loads(data)
    aval_version = values.get("tag_name")
    current_version = myVersion()
    if (version.parse(aval_version) > version.parse(current_version)):
        return aval_version
    else:
        False

# Calls the Java process of kitchen.sh/Kitchen.bat via sys command and parses the outcome
def get_kettle_job_parameter(kitchenPathExe, paramKitchenFile):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen([kitchenPathExe, paramKitchenFile, "-listparam"], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True) # no bla bla java from Pentaho...
    else:
        p = subprocess.Popen([kitchenPathExe, paramKitchenFile, "-listparam"], stdout=subprocess.PIPE, stderr=subprocess.PIPE) # no bla bla java from Pentaho...
    out=p.communicate()[0]
    kettleJobParameterList = {}
    out1 = out.splitlines()
    for line in out1:
        if (line[0:len("Parameter: ")] == "Parameter: "):
            kettleJobParameterList[line.split(" ")[1].split("=")[0]]=line
    return kettleJobParameterList

# This is a better version to get the parameter of a Kettle Job file. 
# The outcome and needed parameters for the function are incompatible to the v1-Version (get_kettle_job_parameter)
# Returns dict
# Example: 
#   paramList["param1"]["name"]="param1"
#   paramList["param1"]["description"]="This is a description from the ktr/kjb"
#   paramList["param1"]["default_value"]="Default value of the transformation, if it is defined"
def get_kettle_job_parameter_v2(ktrOrkjbFile):
    pdi_file=ktrOrkjbFile
    paramList = {}
    # Load XML file (PDI Job file or transformation file)
    xmldoc = minidom.parse(pdi_file)
    job_list = xmldoc.getElementsByTagName('job')
    for job in job_list:
        parameters_list = job.getElementsByTagName('parameters')
        for parameters in parameters_list:
            parameter_list = parameters.getElementsByTagName('parameter')
            for parameter in parameter_list:
                # Get only job or tranformation parameter entries (those have a default_value tag)
                if (len(parameter.getElementsByTagName('default_value'))>0):
                    paramElements = {}
                    paramElements["name"] = parameter.getElementsByTagName('name')[0].childNodes[0].data
                    paramElements["description"] = parameter.getElementsByTagName('description')[0].childNodes[0].data
                    if (len(parameter.getElementsByTagName('default_value')[0].childNodes)):
                        paramElements["default_value"] = parameter.getElementsByTagName('default_value')[0].childNodes[0].data
                    paramList[paramElements["name"]]= paramElements
    return paramList

# the all parameters (old and new) as a whole set
def merge_kettle_parameter_old_new(kettleJobParameterList_old, kettleJobParameterList_new):
    # old and new parameter in one merged dictionary
    merged_dict = kettleJobParameterList_old.copy()
    merged_dict.update(kettleJobParameterList_new)
    return merged_dict

def qualify_kettle_paramter_to_new_old(merged_parameter_list, kettleJobParameterList_old, kettleJobParameterList_new):
    for key in merged_parameter_list:
        if ((key in kettleJobParameterList_old) and (key in kettleJobParameterList_new)):
            # parameter are in both.
            merged_parameter_list[key] = "="      # nothing changed
        else:
            if key in kettleJobParameterList_new:
                merged_parameter_list[key] = "+" # added parameter
            if key not in kettleJobParameterList_new:
                merged_parameter_list[key] = "-" # deleted parameter
    return merged_parameter_list

def find_file(root_folder, rex, found_files, with_index=True, debug=False, quit_after_found_items=10, use_regex=True, file_name=None):
    timeindex_start = time.time()
    timeout_seconds = 60
    if (len(found_files) == 0):
        i=0
    else:
        i=found_files.keys()[-1] # get the last index
    total = quit_after_found_items
    if (debug):
        total=3
        quit_after_found_items=total
        timeout_seconds = 30
    status = 'Searching for files (Max=' + str(quit_after_found_items) + ' or ' + str(timeout_seconds) + ' seconds)'
    progress(i, total, status=status )
    for root,dirs,files in os.walk(root_folder):
        for f in files:
            if (use_regex):
                result = rex.search(f)
            else:
                if (f == file_name):
                    result = True
                else:
                    result = False
            timeindex_check = time.time()
            if ((timeindex_check - timeindex_start) >= timeout_seconds):
                print("\r\n")
                return found_files                
            if (debug and i>=5):
                print("\r\n")
                return found_files
            if (quit_after_found_items is not None):
                if (i>=quit_after_found_items):
                    print("\r\n")
                    return found_files
            if result:
                i=i+1
                progress(i, total, status=status)
                if (root.find(os.path.join('_working_dir','meta_grid-master','bulk_import','kettle')) < 0):                 
                    if (with_index):
                        found_files[i] = os.path.join(root, f)
                    else:
                        found_files[os.path.join(root, f)] = os.path.join(root)
    print("\r\n")
    return found_files

def find_file_in_all_drives(file_name, found_files, with_index=True, debug=False, use_regex=False):
    #create a regular expression for the file
    rex = re.compile(file_name)
    # for drive in win32api.GetLogicalDriveStrings().split('\000')[:-1]:
    for drive in ['%s:' % d for d in string.ascii_uppercase if os.path.exists('%s:' % d)]:
        return find_file( (drive + '\\'), rex, found_files, debug=debug, use_regex=use_regex, file_name=file_name )
    return find_file( '/', rex, found_files, debug=debug, use_regex=use_regex, file_name=file_name)

## https://gist.github.com/vladignatyev/06860ec2040cb497f0f3
def progress(count, total, status=''):
    bar_len = 60
    filled_len = int(round(bar_len * count / float(total)))

    percents = round(100.0 * count / float(total), 1)
    bar = '=' * filled_len + '-' * (bar_len - filled_len)

    sys.stdout.write('[%s] %s%s ...%s\r' % (bar, percents, '%', status))
    sys.stdout.flush()  # As suggested by Rom Ruben (see: http://stackoverflow.com/questions/3173320/text-progress-bar-in-the-console/27871113#comment50529068_27871113)

def RepresentsInt(s):
    try: 
        int(s)
        return True
    except ValueError:
        return False

def get_php_version(phpExe):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen([phpExe, "-r", """echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION;"""], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    else:
        p = subprocess.Popen([phpExe, "-r", """echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION;"""], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    out=p.communicate()[0]
    out1 = out.splitlines()
    return out1[0]

def check_if_php_version_is_ok(installed_php_version, minimun_php_version_needed):
    return (version.parse(installed_php_version) >= version.parse(minimun_php_version_needed))

def yii_check_requirements(phpExe, frontendfilesfolder):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen([phpExe, os.path.join(frontendfilesfolder, "requirements.php")], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    else:
        p = subprocess.Popen([phpExe, os.path.join(frontendfilesfolder, "requirements.php")], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    out=p.communicate()[0]
    out1 = out.splitlines()
    return out1

def yii_metagrid_register_roles(phpExe, frontendfilesfolder):
    yiicmd = "yii"
    if (is_windows()):
        yiicmd = "yii.bat"
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen([phpExe, yiicmd, "metagrid/register-roles"], stdout=subprocess.PIPE, stderr=subprocess.PIPE, cwd=frontendfilesfolder, text=True)
    else:
        p = subprocess.Popen([phpExe, yiicmd, "metagrid/register-roles"], stdout=subprocess.PIPE, stderr=subprocess.PIPE, cwd=frontendfilesfolder)
    out=p.communicate()[0]
    out1 = out.splitlines()
    return out1

# Check, which path of the installation shall be gone
def get_installation_os_candidate():
    import platform
    plf=platform.version().lower()
    if ('ubuntu' in plf):
        return "ubuntu",""
    # We assume, that if apt-get is found, some derivate of Ubuntu... ;-)
    if checkIfToolIsExecutable("apt-get"):
        return "ubuntu",""
    if ('darwin' in plf):
        if ('arm64' in plf):
            return "mac","silicon"
        else:
            return "mac","intel"
    return None

# Check, if Git cli is found. Returns True if executable, and False if not
def checkIfGitIsInstalled():
    return checkIfToolIsExecutable("git")

def which_executable(executable):
    if (int(sys.version_info[0]) >= 3):
        p = subprocess.Popen(["which", executable], stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    else:
        p = subprocess.Popen(["which", executable], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    out=p.communicate()[0]
    result_which_executable = {}
    out1 = out.splitlines()
    result_which_executable = out1
    return result_which_executable

# Additional method of checking, if a tool exists. In the case of *nix OS, which is used to find out the path
def is_tool_executable(executable, ini_user_settings_path, section, item, toolname):
    if (not os.path.exists(executable)):
        if (not is_windows()):
            # on Linux or MAC OS we can try "which php"...
            which_result = which_executable(executable)
            if item.lower()=='javaexe' and get_installation_os_candidate()[0]=="mac": # on MAC there is maybe the pseudo java cli with the hint to download Java... 
                which_result = False
            if not which_result:
                return False, "{} executable ({}) not found! Installatation/Update not completed!".format(toolname, executable)
            else:
                executable = which_result[0] # OK, replace the value
                if (not os.path.exists(executable)):
                    return False, "{} executable ({}) not found! Installatation/Update not completed!".format(toolname, executable)
                else:
                    set_user_settings(ini_user_settings_path, section, item, executable) # overwrite for the next start
                    return True, None
        else:
            return False, "{} executable ({}) not found! Installatation/Update not completed!".format(toolname, executable)
    return True, None

# Credits: https://stackoverflow.com/a/43172958
def is_user_admin():
    # type: () -> bool
    """Return True if user has admin privileges.

    Raises:
        AdminStateUnknownError if user privileges cannot be determined.
    """
    try:
        return os.getuid() == 0
    except AttributeError:
        pass
    try:
        return ctypes.windll.shell32.IsUserAnAdmin() == 1
    except AttributeError:
        raise AdminStateUnknownError

class AdminStateUnknownError(Exception):
    """Cannot determine whether the user is an admin."""
    pass