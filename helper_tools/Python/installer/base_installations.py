#!/usr/bin/python
# -*- coding: utf-8 -*-
# Instllation or update meta#grid
# on every OS
# Base helper library
# v1.8.5

import os, subprocess
import base_lib
from subprocess import check_call, CalledProcessError
import stat
import tempfile

# Get admin rights for Linux and MacOS
def prompt_sudo():
    ret = 0
    if os.geteuid() != 0:
        msg = "[sudo] password for %u: "
        ret = subprocess.check_call("sudo -v -p '%s'" % msg, shell=True)
    return ret

# Create a directory with sudoers permission
def sudo_mkdir(folder_path):
    returncode = os.system("sudo mkdir '{}'".format(folder_path))
    if returncode==0:
        return True, returncode
    else:
        return False, returncode

# Move command with sudoers permission
def sudo_mv(fromfullfilepath, tofromfullfilepath):
    returncode = os.system("sudo mv '{}' '{}'".format(fromfullfilepath, tofromfullfilepath))
    if returncode==0:
        return True, returncode
    else:
        return False, returncode

# Change owner of file/folder
# If no user/group is given, the current user and default group of the session will be used
def sudo_chown(user=None, group=None, folderOrFilePath=None, recursive=False):
    if user is None:
        user='`id -u`'
    if group is None:
        group='`id -g`'
    if folderOrFilePath is None:
        raise Exception("folderOrFilePath can not be None")
    paraR = ""
    if recursive:
        paraR=" -R "
    returncode = os.system("sudo chown {} {}:{} '{}'".format(paraR, user, group, folderOrFilePath))
    if returncode==0:
        return True, returncode
    else:
        return False, returncode

# Change mode of file or folder: User read and execute
# https://stackoverflow.com/a/55590988
def chmod_set_user_exec_right(filePath=None):
    os.chmod(filePath, stat.S_IRUSR | stat.S_IXUSR)

# Change mode of file or folder: User read, execute, others write, read, execute
def chmod_set_others_write(filePath=None):
    os.chmod(filePath, stat.S_IRUSR | stat.S_IXUSR | stat.S_IWOTH | stat.S_IROTH | stat.S_IXOTH)

# Returns file content
def file_as_bytes(file):
    with file:
        return file.read()

# Returns a MD5 checksum
def md5file(full_path):
    import hashlib
    return hashlib.md5(file_as_bytes(open(full_path, 'rb'))).hexdigest()

# Returns for given filename a predefined and valid checksum
def md5checksum_downloads(what=None):
    if what is None:
        return None
    if what=="pdi-ce.zip":
        return 'ad310b1951aacb813d5b8f52b1b82bc6'
    if what=="liquibase.zip":
        return '15eb971549ca6e36679809eb14aece32'
    if what=="pentaho-pdi-plugin-jdbc-metadata.zip":
        return '51d60d376225a2e913a4bca1ee868a5d'
    if what=="dwh_meta_clean.sqlite":
        return '3a4ea09e39e810e8b63ebb824953f2b8'
    if what=="dwh_meta_demo.sqlite":
        return '4bb17b806bb1cf33daeb381afac300b3'
    return None

# Touch a file
def touch(fname, times=None):
    with open(fname, 'a'):
        os.utime(fname, times)

# Returns a random string
# Credits: https://pynative.com/python-generate-random-string/
def get_random_string(length=50):
    import random
    import string

    # choose from all lowercase letter
    letters = string.ascii_lowercase
    result_str = ''.join(random.choice(letters) for i in range(length))
    return result_str

# Do common parts for *nix OS
def nix_common_1(ignore_version_check=False, clean_or_demo_db="demo"):
    # Check the version output
    version_err = False
    if not ignore_version_check:
        base_lib.bla(msg="- Print versions", action="header")
        try:
            for v in [["php", "-v"],["java","-version"],["python3","-V"]]:
                base_lib.bla(msg=v[0]+":", action="subheader")
                check_call(v)
        except CalledProcessError as e:
            base_lib.bla(msg=e.output, action="error")
            version_err = True
        if version_err:
            base_lib.bla(msg="Error happened at calling versions output. Exit.", action="error")
            return False
    tmpDir = tempfile.gettempdir()
    mgInstDir = os.path.join(os.path.sep, "opt", 'meta_grid')
    mgFrontendInstDir = os.path.join(mgInstDir, "frontend")
    mgSQLiteInstDir = os.path.join(mgInstDir, "db")
    mgEtcDir = os.path.join(mgInstDir, "etc")
    mgDBChangelogDir = os.path.join(mgInstDir, "etc", "meta_grid_database_changelog")
    mgBulkImportInstDir = os.path.join(mgInstDir, "bulkimport")
    mg3rdPartyInstDir = os.path.join(mgInstDir, "3rd_party_tools")
    # Create dirs with admin-priviliges

    base_lib.bla(msg="- The application files will be installed to:", action="header")
    for dir in [mgInstDir, mgFrontendInstDir, mgSQLiteInstDir, mgBulkImportInstDir, mg3rdPartyInstDir, mgEtcDir, mgDBChangelogDir]:
        status, returncode = sudo_mkdir(dir)
        if not status:
            return False
        status, returncode = sudo_chown(user=None, group=None, folderOrFilePath=dir)
        if not status:
            return False
        base_lib.bla(msg="- {}".format(dir), action="subheader")

    base_lib.bla(msg="- Download 3rd party tools", action="header")

    # LiquiBase
    fname_download = 'liquibase.zip'
    if os.path.isfile(os.path.join(tmpDir, fname_download)) and md5file(os.path.join(tmpDir, fname_download)) == md5checksum_downloads(fname_download):
        base_lib.bla(msg="- - {} already exists. Checksum matches. We will use this file.".format(fname_download), action="OK")
        pass
    else:
        base_lib.bla(msg="- - Download {}".format(fname_download), action="subheader")
        base_lib.download_file("https://github.com/liquibase/liquibase/releases/download/v3.8.9/liquibase-3.8.9.zip", os.path.join(tmpDir, fname_download))
    base_lib.extract_zip(os.path.join(tmpDir, fname_download), os.path.join(mg3rdPartyInstDir, 'liquibase'))
    chmod_set_user_exec_right(os.path.join(mg3rdPartyInstDir, 'liquibase', 'liquibase')) # chmod +x /opt/meta_grid/3rd_party_tools/liquibase/liquibase

    # SQLite JDBC
    base_lib.bla(msg="- - Download {}".format('sqlite-jdbc-3.32.3.2.jar'), action="subheader")
    base_lib.download_file("https://github.com/xerial/sqlite-jdbc/releases/download/3.32.3.2/sqlite-jdbc-3.32.3.2.jar", os.path.join(tmpDir, 'sqlite-jdbc-3.32.3.2.jar'))
    os.rename(os.path.join(tmpDir, 'sqlite-jdbc-3.32.3.2.jar'), os.path.join(mg3rdPartyInstDir, 'liquibase', 'lib', 'sqlite-jdbc-3.32.3.2.jar'))

    # PDI
    fname_download = 'pdi-ce.zip'
    if os.path.isfile(os.path.join(tmpDir, fname_download)) and md5file(os.path.join(tmpDir, fname_download)) == md5checksum_downloads(fname_download):
        base_lib.bla(msg="- - {} already exists. Checksum matches. We will use this file.".format(fname_download), action="OK")
        pass
    else:
        base_lib.bla(msg="- - Download {}".format(fname_download), action="subheader")
        # base_lib.download_file("https://downloads.sourceforge.net/project/pentaho/Pentaho%208.1/client-tools/pdi-ce-8.1.0.0-365.zip", os.path.join(tmpDir, fname_download))
        base_lib.download_file("https://github.com/Meta-Grid/3rd_party_tools/releases/download/pdi/pdi-ce-8.1.0.0-365.zip", os.path.join(tmpDir, fname_download))
    base_lib.extract_zip(os.path.join(tmpDir, fname_download), os.path.join(mg3rdPartyInstDir))
    
    # PDI JDBC Metadata Plugin
    fname_download = 'pentaho-pdi-plugin-jdbc-metadata.zip'
    if os.path.isfile(os.path.join(tmpDir, fname_download)) and md5file(os.path.join(tmpDir, fname_download)) == md5checksum_downloads(fname_download):
        base_lib.bla(msg="- - {} already exists. Checksum matches. We will use this file.".format(fname_download), action="OK")
        pass
    else:
        base_lib.bla(msg="- - Download {}".format(fname_download), action="subheader")
        base_lib.download_file("https://raw.githubusercontent.com/rpbouman/pentaho-pdi-plugin-jdbc-metadata/master/dist/steps/pentaho-pdi-plugin-jdbc-metadata/pentaho-pdi-plugin-jdbc-metadata.zip", os.path.join(tmpDir, fname_download))
    base_lib.extract_zip(os.path.join(tmpDir, fname_download), os.path.join(mg3rdPartyInstDir, 'data-integration', 'plugins', 'steps'))
    
    # Get clean or demo database file
    fname_download = 'dwh_meta_clean.sqlite'
    if clean_or_demo_db=="demo":
        fname_download = 'dwh_meta_demo.sqlite'
    if os.path.isfile(os.path.join(tmpDir, fname_download)) and md5file(os.path.join(tmpDir, fname_download)) == md5checksum_downloads(fname_download):
        base_lib.bla(msg="- - {} already exists. Checksum matches. We will use this file.".format(fname_download), action="OK")
        pass
    else:
        base_lib.bla(msg="- - Download {}".format(fname_download), action="subheader")
        base_lib.download_file("https://raw.githubusercontent.com/patschwork/meta_grid/master/db/{}".format(fname_download), os.path.join(tmpDir, fname_download))
    os.rename(os.path.join(tmpDir, fname_download), os.path.join(mgSQLiteInstDir, 'dwh_meta.sqlite'))
  
    # Get initial bulk-importer file
    fname_download = 'run_import.kjb'
    if os.path.isfile(os.path.join(tmpDir, fname_download)) and md5file(os.path.join(tmpDir, fname_download)) == md5checksum_downloads(fname_download):
        base_lib.bla(msg="- - {} already exists. Checksum matches. We will use this file.".format(fname_download), action="OK")
        pass
    else:
        base_lib.bla(msg="- - Download {}".format(fname_download), action="subheader")
        base_lib.download_file("https://raw.githubusercontent.com/patschwork/meta_grid/master/bulk_import/kettle/run_import.kjb", os.path.join(tmpDir, fname_download))
    os.rename(os.path.join(tmpDir, fname_download), os.path.join(mgBulkImportInstDir, fname_download))
  
    # Create dummy file
    touch(os.path.join(mgFrontendInstDir, "gii_crud.sh"))

    # Create the install_settings.ini
    currentFile = __file__ 
    realPath = os.path.realpath(currentFile)
    dirPath = os.path.dirname(realPath)
    ini_user_settings_path = os.path.join(dirPath,"install_settings.ini")

    section="meta_grid_source"
    base_lib.set_user_settings(ini_user_settings_path, section, 'git_repo_zip_url', "https://github.com/patschwork/meta_grid/archive/master.zip")
    
    section="current_installation"
    base_lib.set_user_settings(ini_user_settings_path, section, 'folderfile_Database', os.path.join(mgSQLiteInstDir, "dwh_meta.sqlite"))
    
    section="current_installation"
    base_lib.set_user_settings(ini_user_settings_path, section, 'folder_Frontend', mgFrontendInstDir)    
    base_lib.set_user_settings(ini_user_settings_path, section, 'folder_Bulkimport', mgBulkImportInstDir)
    base_lib.set_user_settings(ini_user_settings_path, section, 'folder_MetaGrid_Database_Changelog', mgDBChangelogDir)

    section="tools"
    base_lib.set_user_settings(ini_user_settings_path, section, 'liquibasePathExe', os.path.join(mg3rdPartyInstDir, 'liquibase', 'liquibase'))
    base_lib.set_user_settings(ini_user_settings_path, section, 'pythonExe', "python3")
    base_lib.set_user_settings(ini_user_settings_path, section, 'phpExe', "php")
    base_lib.set_user_settings(ini_user_settings_path, section, 'kitchenPathExe', os.path.join(mg3rdPartyInstDir, "data-integration", "kitchen.sh"))

    # Back to the updater... 

    return True

# Do MacOS stuff
def inst_mac(clean_or_demo_db):
    if prompt_sudo() != 0:
        base_lib.bla("The installation process needs sudo rights to install system components.", action="error")
        base_lib.bla("You may also start the installer with sudo or as root.")
        return False
    else:
        # Homebrew for installing components?!
        return nix_common_1(ignore_version_check=True, clean_or_demo_db=clean_or_demo_db)

# Do Ubuntu (or derivates) related stuff (mainly because of the package manager apt)
def inst_ubuntu(clean_or_demo_db):
    if prompt_sudo() != 0:
        base_lib.bla("The installation process needs sudo rights to install system components.", action="error")
        base_lib.bla("You may also start the installer with sudo or as root.")
        return False
    else:
        software_packages = "sudo apt-get install -y apache2 libapache2-mod-php php-cli php-common php-curl php-gd php-json php-mbstring mcrypt php-opcache php-readline php-sqlite3 php-xml php-xmlrpc php-xsl php-zip php-intl composer sqlite3 python3 openjdk-8-jre"
        try:
            base_lib.bla(msg="- Install needed software packages via apt-get install", action="header")
            base_lib.bla(msg=software_packages)
            check_call(software_packages.split(" "), stdout=open(os.devnull,'wb'))
        except CalledProcessError as e:
            base_lib.bla(msg=e.output, action="error")
            return False
        if nix_common_1(clean_or_demo_db=clean_or_demo_db):
            return True
        else:
            return False

# Search in the file content (frontend configs) for a string. Returns True if found.
def search_for_config_string(fullfilename, encoding, stringToFind):
    with open(fullfilename, mode="r", encoding=encoding) as f:
        if stringToFind in f.read():
            return True
    return False

# Replace a string in a given config file (php file). Returns True if sucessful.
def set_new_config_string(fullfilename, old_string="", new_string="", encoding=None):
    if (new_string=="" or old_string==""):
        return False
    try:
        if fullfilename is None or new_string=="":
            raise Exception("Nothing to change!")
        with open(fullfilename, mode='r', encoding=encoding) as file:
            filedata = file.read()
        filedata = filedata.replace(old_string, new_string)
        with open(fullfilename, 'w', encoding=encoding) as file:
            file.write(filedata)
        return True, None
    except Exception as e:
        return False, str(e)

# Establish a connection to a SQLite database file. Returns connection.
# Credits: https://www.sqlitetutorial.net/sqlite-python/update/
def create_sqlite_connection(db_file):
    """ create a database connection to the SQLite database
        specified by the db_file
    :param db_file: database file
    :return: Connection object or None
    """
    import sqlite3
    conn = None
    try:
        conn = sqlite3.connect(db_file)
    except Exception as e:
        base_lib.bla(msg=str(e), action="error")

    return conn

# Do SQL update on table app_config.
# Credits: https://www.sqlitetutorial.net/sqlite-python/update/
def update_app_config_table(conn, updates):
    """
    update priority, begin_date, and end date of a task
    :param conn:
    :param task:
    :return: project id
    """
    sql = ''' UPDATE app_config
              SET valueSTRING = ?
              WHERE key = ?'''
    cur = conn.cursor()
    cur.execute(sql, updates)
    conn.commit()

# Do SQL update on table user.
def update_app_user_table(conn, updates):
    """
    update priority, begin_date, and end date of a task
    :param conn:
    :param task:
    :return: project id
    """
    sql = ''' UPDATE user
              SET password_hash = ?
              WHERE id = ?'''
    cur = conn.cursor()
    cur.execute(sql, updates)
    conn.commit()

# Returns text for a VirtualHost to be usable by Apache2.
def set_apache_vhost_file_content(ServerAdmin="webmaster@localhost", ServerAlias="meta_grid", AliasURL="/meta_grid", PathToFrontend="/opt/meta_grid/frontend/web", logPath='${APACHE_LOG_DIR}'):
    return """
<VirtualHost *:80>
    ServerAdmin {}

    ServerAlias {}
    Alias {} {}

    DocumentRoot {}
    <Directory {}>
        Options -Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
    </Directory>

    LogLevel warn
    ErrorLog {}/{}_error.log
    CustomLog {}/{}_access.log combined

</VirtualHost>
    """.format(ServerAdmin, ServerAlias, AliasURL, PathToFrontend+os.path.sep, PathToFrontend, PathToFrontend, logPath, ServerAlias, logPath, ServerAlias)

# After the updater was doing things (no OS dependency)
# (is called from meta-grid_install_or_update.py)
def after_update_todos():
    # INSTALLATION PART 2
    databasebackendsystem="sqlite"

    # get install_settings.ini
    currentFile = __file__ 
    realPath = os.path.realpath(currentFile)
    dirPath = os.path.dirname(realPath)
    ini_user_settings_path = os.path.join(dirPath,"install_settings.ini")

    section="current_installation"
    folder_Frontend=base_lib.get_user_settings(ini_user_settings_path, section, 'folder_Frontend', "")
    folderfile_Database=base_lib.get_user_settings(ini_user_settings_path, section, 'folderfile_Database', "")
    folder_MGDatabaseChangelog=base_lib.get_user_settings(ini_user_settings_path, section, 'folder_MetaGrid_Database_Changelog', "")
    
    # Create assets folder (if already exists, ignore)
    try:
        os.mkdir(os.path.join(folder_Frontend, 'web', 'assets'))
    except:
        return False, "Could not create {}".format(os.path.join(folder_Frontend, 'web', 'assets'))

    # Set random cookieValidationKey to config/web.php
    frontend_config_web_fullfilename = os.path.join(folder_Frontend, 'config', 'web.php')
    encoding='ISO 8859-1' # We have to use the enconding setting, because the file was created with Windows encoding....
    
    string_to_find="'cookieValidationKey' => 'xxx',"
    randStr = get_random_string()
    if search_for_config_string(fullfilename=frontend_config_web_fullfilename, encoding=encoding, stringToFind=string_to_find):
        ret, error = set_new_config_string(fullfilename=frontend_config_web_fullfilename, old_string="'cookieValidationKey' => 'xxx',", new_string="'cookieValidationKey' => '{}',".format(randStr), encoding=encoding)
        if ret:
            base_lib.bla(msg="- CookieValidationKey changed", action="header")
        else:
            base_lib.bla(msg="Count not set a new CookieValidationKey!", action="error")
            base_lib.bla(msg=error, action="error")
            return False, None

    if databasebackendsystem=="sqlite":

        # Set relative path to database file in config/db.php
        frontend_config_db_fullfilename = os.path.join(folder_Frontend, 'config', 'db.php')
        get_relative_path_frontend_to_db = os.path.relpath(folderfile_Database, os.path.join(folder_Frontend, 'config'))
        string_to_find="'dsn' => 'sqlite:../../../../dwh_meta.sqlite',"
        string_to_replace="'dsn' => 'sqlite:{}',".format(get_relative_path_frontend_to_db)

        if search_for_config_string(fullfilename=frontend_config_db_fullfilename, encoding=encoding, stringToFind=string_to_find):
            ret, error = set_new_config_string(fullfilename=frontend_config_db_fullfilename, old_string=string_to_find, new_string=string_to_replace, encoding=encoding)
            if ret:
                base_lib.bla(msg="- dsn changed", action="header")
            else:
                base_lib.bla(msg="Count not set a new dsn!", action="error")
                base_lib.bla(msg=error, action="error")
                return False, None
        
        conn = create_sqlite_connection(folderfile_Database)
        path_to_lqb_changelog_file = os.path.join(folder_MGDatabaseChangelog, 'db.changelog-master.xml') # the update copies the file to same place, where dwh_meta.sqlite lives
        with conn:
            update_app_config_table(conn, (path_to_lqb_changelog_file, 'liquibase_changelog_master_filepath'))
            update_app_user_table(conn, ('$2y$10$JrSvAr97kTHukJS9DfLGju0gC7BCal5f/nPvJkcFNF6qTBQ1HKK1y', 1)) # in case the admin user password is not "admin". This is the case of dwh_meta_demo.sqlite
        conn.close()

    # Generate a Apache vHost file
    tmpApacheVhostFile = os.path.join(tempfile.gettempdir(), "meta_grid.conf")
    vhost_content = set_apache_vhost_file_content()
    try:
        with open(tmpApacheVhostFile, 'w') as file:
            file.write(vhost_content)
        pass
    except Exception as e:
        return False, str(e)

    chmod_set_others_write(os.path.dirname(folderfile_Database)) # chmod o+w /opt/meta_grid/db --> e.g. www-data must be able to write. # TODO maybe not working for Windows?

    get_installation_os_candidate=base_lib.get_installation_os_candidate()
    if get_installation_os_candidate[0]=="ubuntu":
        ret, err = after_update_todos_ubuntu(tmpApacheVhostFile, folder_Frontend, folderfile_Database)
        if not ret:
            return False, err
    return True, None

# Do Ubuntu (or derivates) related stuff
def after_update_todos_ubuntu(tmpApacheVhostFile, folder_Frontend, folderfile_Database):
    if prompt_sudo() != 0:
        base_lib.bla("The installation process needs sudo rights to install system components.", action="error")
        base_lib.bla("You may also start the installer with sudo or as root.")
        return False, None
    else:
        sudo_mv(tmpApacheVhostFile, os.path.join(os.path.sep, "etc", "apache2", "sites-available", "meta_grid.conf"))

        # Apache deactivate default
        returncode = os.system("sudo a2dissite 000-default.conf")
        if returncode==0:
            pass
        else:
            return False, returncode

        # Apache activate site
        returncode = os.system("sudo a2ensite meta_grid")
        if returncode==0:
            pass
        else:
            return False, returncode
        
        # Apache service restart
        returncode = os.system("sudo service apache2 restart")
        if returncode==0:
            base_lib.bla(msg="sudo service apache2 restart")
            pass
        else:
            return False, returncode

        # Chown
        sudo_chown(user="www-data", group="www-data", folderOrFilePath=os.path.join(folder_Frontend, 'web', 'assets'), recursive=True)
        sudo_chown(user="www-data", group="www-data", folderOrFilePath=os.path.join(folder_Frontend, 'runtime'), recursive=True)
        
        sudo_chown(user="www-data", group="www-data", folderOrFilePath=folderfile_Database)

        return True, None

# Things do be done, before the updater is doing things...
# (is called from meta-grid_install_or_update.py)
def before_update_todos(clean_or_demo_db="demo"):
    get_installation_os_candidate = base_lib.get_installation_os_candidate()
    
    # INSTALLATION PART 1
    if get_installation_os_candidate[0]=="ubuntu":
        return inst_ubuntu(clean_or_demo_db)
    if get_installation_os_candidate[0]=="mac":
        return inst_mac(clean_or_demo_db)
    
    base_lib.bla(msg="Your current OS is currently not supported.", action="error")
    return False


if __name__ == '__main__':
    pass
    # get_installation_os_candidate = base_lib.get_installation_os_candidate()
    
    # # PART 1
    # if get_installation_os_candidate[0]=="ubuntu":
    #     inst_ubuntu()
    # if get_installation_os_candidate[0]=="mac":
    #     inst_mac()


    # RUN UPDATER!!

    # PART 2
    # after_update_todos()
    
    # tmpApacheVhostFile = os.path.join(os.path.sep, "tmp", "meta_grid.conf")

    # # get install_settings.ini
    # currentFile = __file__ 
    # realPath = os.path.realpath(currentFile)
    # dirPath = os.path.dirname(realPath)
    # ini_user_settings_path = os.path.join(dirPath,"install_settings.ini")

    # section="current_installation"
    # folder_Frontend=base_lib.get_user_settings(ini_user_settings_path, section, 'folder_Frontend', "")
    # folderfile_Database=base_lib.get_user_settings(ini_user_settings_path, section, 'folderfile_Database', "")


    # if get_installation_os_candidate[0]=="ubuntu":
    #     after_update_todos_ubuntu(tmpApacheVhostFile, folder_Frontend, folderfile_Database)
