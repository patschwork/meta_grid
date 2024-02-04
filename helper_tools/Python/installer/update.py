# This script will download, install/update the Meta#Grid Installer-and-Updater-Tool.
# This version should run on every OS. On Windows you may also use update.ps1 for a PowerShell alternative.

import tempfile
import sys
if (int(sys.version_info[0]) >= 3):
    from urllib.request import urlopen
else:
    from urllib2 import urlopen
import json
import os

class Updater:
    def __init__(self) -> None:
        # Defining some constants
        self.this_app_path = os.path.dirname(os.path.realpath(__file__ ))
        self.url_repo="patschwork/meta_grid_install_update"
        self.git_hub_releases_api_url = "https://api.github.com/repos/{}/releases/latest".format(self.url_repo)
        self.tmpDir = tempfile.gettempdir()
        # self.tmpDir = "/tmp"
        self.tmpDownloadDir = os.path.join(self.tmpDir, "mg_updatetool")
        self.tmpExtractDir = os.path.join(self.tmpDir, "mg_updatetool", "meta_grid_updater")
        self.mg_installer_updater_tool_zip = "meta_grid_updater.zip"
        self.latest = None

    # Return the newest release tag
    # Taken from base_lib.check_if_a_newer_version_is_available
    def get_latest_release(self):
        try:
            response = urlopen(self.git_hub_releases_api_url)
            data = response.read()
            values = json.loads(data)
            aval_version = values.get("tag_name")
            return aval_version
        except:
            print("Error by getting the last release tag from GitHub")
            return None

    def get_latest_download_url(self):
        self.latest = self.get_latest_release()
        return "https://github.com/{}/releases/download/{}/{}".format(self.url_repo, self.latest, self.mg_installer_updater_tool_zip)

    # Taken from base_lib.download_file_simple
    def download_file_simple(self, url, download_to):
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

    # Taken from base_lib.extract_zip
    def extract_zip(self, zip_file, to_folder=""):
        try:
            import zipfile
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

    # Move files to destination
    def replace_and_move(self, src, dst):
        import os
        import shutil

        root_src_dir = src
        root_dst_dir = dst

        for src_dir, dirs, files in os.walk(root_src_dir):
            dst_dir = src_dir.replace(root_src_dir, root_dst_dir, 1)
            if not os.path.exists(dst_dir):
                os.makedirs(dst_dir)
            for file_ in files:
                src_file = os.path.join(src_dir, file_)
                dst_file = os.path.join(dst_dir, file_)
                if os.path.exists(dst_file):
                    # in case of the src and dst are the same file
                    if os.path.samefile(src_file, dst_file):
                        continue
                    os.remove(dst_file)
                shutil.move(src_file, dst_dir)

    # Doing the update things
    def do_things(self):
        import shutil
        try:
            try:
                shutil.rmtree(self.tmpDownloadDir, ignore_errors=True)
            except:
                pass
            # make dir
            os.mkdir(self.tmpDownloadDir)
            os.mkdir(self.tmpExtractDir)
        except:
            raise Exception("Can't create temp. downloadfolder: {}".format(self.tmpDownloadDir))
        
        download_to=os.path.join(self.tmpDownloadDir, self.mg_installer_updater_tool_zip)
        download_url=self.get_latest_download_url()
        print("Download from {}".format(download_url))
        self.download_file_simple(url=download_url, download_to=download_to)
        print("Extract")
        res=self.extract_zip(download_to, self.tmpExtractDir)
        if res:
            print("Update files")
            self.replace_and_move(src=self.tmpExtractDir, dst=self.this_app_path)
            print("Finished. Meta#Grid Installer-and-Updater-Tool was installed/updated to {}".format(self.latest))
            print("")
            print("You may run meta-grid_install_or_update.py to install Meta#Grid e.g. python3 meta-grid_install_or_update.py")
            exit(0)
        else:
            print("Could not extract the archive file from {}".format(download_to))
            exit(1)

if __name__ == "__main__":
    updater = Updater()
    updater.do_things()