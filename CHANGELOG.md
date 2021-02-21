# 2.4.1   

- [T114]                    New elements in config/web.php (Admin-Hint on main-page with instruction!!)
- [T84 | GitHub Issue #1]   Inline description and comments from database views will be imported with the bulk loader (new bulk loader parameter!!)

- [T72]                     Flowing header for gridviews (useful when scrolling). Can be disabled in table app_config
- [T73]                     ContactGroup is now optional in object_types (contact, data_delivery_object, sourcesystem)
- [T80 | GitHub Issue #5]   Bugfix -> Password reset
- [T82 | GitHub Issue #3]   Filtering now avaiable for column "database" (db_table and db_table_fields)
- [T83 | GitHub Issue #2]   Bugfix -> Filtering on client not working
- [T85]                     Column "Database" at same position object_types db_table and db_table_field
- [T93]                     In db_table there is a example for a SQL query (SELECT and CREATE TABLE)
- [T92]                     Bugfix -> From db_table_field the "eye" icon had no function (double click was still ok)
- [T94 | GitHub Issue #7]   Change Mapping direction
- [T98]                     Name of client or project are shown. Relation to [T101]
- [T100]                    Bugfix -> Items missing in Mappingsearch-Object-List
- [T101]                    Filtering in mapping dialog on objects of the same client or project like the parent
- [T102]                    data_transfer_type is now listed within Mappingsearch-Object-List
- [T113]                    Orphaned db_table_field items can be cleaned
- [T108]                    Example for a script to execute a bulkloader import (common parameter are prefilled). Copy&Paste avaiable to copy the script; See db_database
- [T109]                    Mappings can be deleted directly in mappinglist of an object_type
- [T110  | GitHub Issue #6] Object_types db_table and db_table_field can be exported as CSV-file (GridView filters are used if set; ussing of the same security model)
- [T112]                    New parameter entries in app_config: bulk_loader_executable, bulk_loader_metagrid_jdbc_url. Relation to [T108]
- [T115]                    On creation of new clients or projects will the creating user be granted for them in the same step (no separate mapping needed)
- [T119]                    (BETA) Import Database Table Metadata via Copy&Paste (needs activation of beta features in table app_config)
                            
- [T118]                    Security patch for the Yii2 framework (GHSA-699q-wcff-g9mj)

- [T104]                    Minor bugfix
- [T105]                    Minor bugfix
- [T107]                    Minor bugfix

- [T87]                     Python Installer/Updater now includes library colorama
- [T117]                    Installer/Updater enhancement (bulk loader files will be copied, wasn't yet)
- [T122]                    Installer/Updater enhancement (version checker)
- [T123]                    Installer/Updater enhancement (new bulk loader parameter will be shown when updating)
- [T124]                    Installer/Updater enhancement (search dialog for folder and files)

----------------------------------------------------------------------------------------------------

# 2.4

- [T52] MultipleTableFieldEdit introduced: Object typed db_table and db_table_fields will now be added and edited in one view
- [T51] New attributes to flag GDPR fields
- [T67] Warning to use PRAGMA foreign_keys = ON 
- [T64] Missing fields in advanced search of each object type added
- [T53] A new column "Database" (index view) in object type "db_table_field" of the inherited attribute from "db_table" was added
- [T55] Avaiable table context in object type "db_table" now depends on selected projekt
- [T69] Centralized location for meta#grid application version
- [T43] Referential integrity for SQLite enabled 
- [T33] If objects will be deleted also comments and mappings will be deleted (incl. cleanup of existing databases)

- [T61] Minor bugfix
- [T60] Minor bugfix
- [T59] Minor bugfix
- [T57] Minor bugfix
- [T56] Minor bugfix
- [T50] Minor bugfix
- [T49] Minor bugfix

- [T66] Release test v2.4

----------------------------------------------------------------------------------------------------

# 2.3

Phabricator-task: Ref T34, T36, T30, T25, T39, T38, T35, T29, T26 


- [T25] Import database stored procedures via bulk loader (actually only MSSQL and PGSQL supported)
- [T26] New object type "url" to add links to related objects (via mapping)
- [T30] Show basic parent object infos in mapping dialog
- [T34] Hint for admins when database structure differs from avaiable (not yet updated) source

- [T29] Bugfix: Non admin user couldn't see any mapping objects in mapping dialog
- [T35] Minor bugfix
- [T36] Minor bugfix
- [T38] Minor bugfix
- [T39] Minor bugfix
