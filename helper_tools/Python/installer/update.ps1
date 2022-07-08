# useful links
#   https://gist.github.com/MarkTiedemann/c0adc1701f3f5c215fc2c2d5b1d5efd3

# Set-ExecutionPolicy, so that this script can go over to work ;-)
& Set-ExecutionPolicy -ExecutionPolicy Unrestricted -Scope Process

$currentdir=$PWD
$ScriptPath = [io.path]::GetDirectoryName($MyInvocation.MyCommand.Path)
$destdir=$ScriptPath
$url_repo="patschwork/meta_grid_install_update"
$file="meta_grid_updater.zip"
$tempDir = [System.IO.Path]::GetTempPath()

cd $ScriptPath

# get latest version
$releases = "https://api.github.com/repos/$url_repo/releases/latest"
$latest = (Invoke-WebRequest $releases | ConvertFrom-Json)[0].tag_name
$download = "https://github.com/$url_repo/releases/download/$latest/$file"

# use temp directory
cd $tempDir
rm -R "mg_updatetool"
mkdir "mg_updatetool" | Out-Null
cd "mg_updatetool"

# wget:
Invoke-WebRequest $download -OutFile $file

# unzip:
Add-Type -assembly "system.io.compression.filesystem"
$zipFilePath = (Get-ChildItem $file).fullname
mkdir "meta-grid_install_or_update" | Out-Null
$extractPath=(Resolve-Path ".\meta-grid_install_or_update").Path
$zip = [System.IO.Compression.ZipFile]::Open($zipFilePath, 'read')
[System.IO.Compression.ZipFileExtensions]::ExtractToDirectory($zip, $extractPath)

# make a backup
cp "$destdir\install_settings.ini" "$destdir\install_settings.ini_BACKUP"
cp "$destdir\install_settings.ini" ".\install_settings.ini_BACKUP"

# copy to destination (where all started)
cp -R -Force meta-grid_install_or_update\* $destdir\

# clean up
rm -R meta-grid_install_or_update

cd $currentdir