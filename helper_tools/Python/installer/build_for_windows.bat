@echo off

pyinstaller --distpath dist_windows --clean --icon="meta#grid_logo_icon.ico" --onefile meta-grid_install_or_update.py
pause
