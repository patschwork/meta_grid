#!/usr/bin/python
# -*- coding: utf-8 -*-
# Instllation or update meta#grid
# on every OS
# Base helper library
# v0.1

import os
import subprocess
import zipfile
import shutil
import errno
from distutils.dir_util import copy_tree
import ConfigParser
from termcolor import colored
from termcolor import cprint
import datetime
import urllib2
import colorama

def bla(msg, action=None, withLooging=True, logfilepath="", logfile=""):
    colorama.init()
    if (action=="intro"):
        cprint(msg, 'white', attrs=['bold'])
    elif (action=="header"):
        cprint(msg, 'white', 'on_blue')
    elif (action=="subheader"):
        color='blue'
        if (is_windows):
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
    elif (action=="endOfScript"):
        color='grey'
        if (is_windows):
            color='green'
        cprint(msg, color, 'on_yellow', attrs=['bold'])
    else:
        print msg
    
    if (withLooging) & (logfilepath != "") & (logfile != "") & (msg != None):
        writeOutputLog(logfilepath, logfile, msg)

# Credits to: https://www.novixys.com/blog/python-check-file-can-read-write/
def check_file_writable(fnm):
    if os.path.exists(fnm):
        # path exists
        if os.path.isfile(fnm): # is it a file or a dir?
            # also works when file is a link and the target is writable
            return os.access(fnm, os.W_OK)
        else:
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

def checkIfGitIsInstalled():
    returnValue=False
    try:
        # pipe output to /dev/null for silence
        null = open(os.devnull, "w")
        subprocess.Popen("git", stdout=null, stderr=null)
        null.close()
        returnValue=True
    
    except OSError:
        print("git not found")
        returnValue=False
    return returnValue

def gitCloneToPath(repo_url, path_to_clone):
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

def download_file(url, download_to):
    try:
        filedata = urllib2.urlopen(url)
        datatowrite = filedata.read()
        with open(download_to, 'wb') as f:
            f.write(datatowrite)
        if (os.path.exists(download_to)):
            return True
        else:
            raise Exception("Error on download_file. File doesn't exists.")
    except:
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
    r_input = raw_input(msg)
    if (r_input == ""):
        r_input = default_value
    return r_input

def get_input_color(msg, default_value="", init_value="", no_input_just_inform_user=False):
    colorama.init()
    if (default_value != ""):
        msg = msg + colored(" [" + default_value + "]", 'green') + ""
    if (init_value != "") & (init_value != default_value):
        color='blue'
        if (is_windows):
            color='cyan'
        msg = msg + colored(" (Inital value: " + init_value + ")", color) + ""
    msg = msg + ": "
    if (not no_input_just_inform_user):
        r_input = raw_input(msg)
        if (r_input == ""):
            r_input = default_value
    else:
        print(msg) + default_value
        r_input = default_value
    return r_input

def intro_get_input_color():
    colorama.init()
    msg = colored("Setting", None, attrs=['bold'])
    msg = msg + colored(" [" + "Last setting -> \"Type Enter\" to choose" + "]", 'green', attrs=['bold']) + ""
    color='blue'
    if (is_windows):
        color='cyan'
    msg = msg + colored(" (Inital value: " + "This is the orginal value. Copy & Paste to use again." + ")", color, attrs=['bold']) + ""
    print(msg)

def get_user_settings(cfgFile, section, key, default_if_key_not_exists=""):
    config=ConfigParser.SafeConfigParser()
    config.read(cfgFile)
    if (not config.has_section(section)):
        return default_if_key_not_exists
    if (not config.has_option(section, key)):
        return default_if_key_not_exists
    return config.get(section,key)

def set_user_settings(cfgFile, section, key, value):
    config=ConfigParser.SafeConfigParser()
    config.read(cfgFile)
    if (not config.has_section(section)):
        config.add_section(section)
    config.set(section,key,value)
    with open(cfgFile, 'wb') as configfile:
        config.write(configfile)

def is_windows(): 
    if os.name == 'nt':
        return True
    else:
        return False