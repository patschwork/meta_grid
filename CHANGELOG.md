# 3.0
New UI and UX, updated Yii2-Framework and components, several bugfixes
- [T211]                    Update to Yii2 v2.0.45
- [T209]                    New UI
- [T209]                    Using AdminLTE3 as UI framework
- [T221]                    Referenced objects can now be created in the same form (don't need to switch and re-enter everything)
- [T260]                    All import and export functionality of Meta#Grid is available on the main nav menu
- [T245]                    Redesign breadcumbs (now showing clients or projects) between name/title of object
- [T259]                    Objecttypes renaming e.g. Attributes are now Patterns
- [T255]                    Roles and permissions are refreshed with the updating tool (see also Meta#Grid Installer/Updater v1.7). 
                            A cli for this now available to do this also the manual way if needed.
- [T236]                    List of tags for direct select to the user on the (new) left side-panel
- [T237]                    No "hard" errors if permissions for user is only partial (e.g. client). A hint is given as output.
- [T218]                    After switching the language/perspective stay at the location (old behaviour: changing to home)
- [T203]                    Meta#Grid Installer/Updater accepts PHP up to 8.1.2 (see also Meta#Grid Installer/Updater v1.7).
- [T212]                    Minor update / common bugfix
- [T216]                    Minor update / common bugfix
- [T219]                    Minor update / common bugfix
- [T234]                    Minor update / common bugfix
- [T223]                    Minor update / common bugfix
- [T225]                    Minor update / common bugfix
- [T226]                    Minor update / common bugfix
- [T227]                    Minor update / common bugfix
- [T232]                    Minor update / common bugfix
- [T233]                    Minor update / common bugfix
- [T239]                    Minor update / common bugfix
- [T242]                    Minor update / common bugfix
- [T217]                    Minor update / common bugfix
- [T243]                    Minor update / common bugfix
- [T215]                    Minor update / common bugfix
- [T241]                    Minor update / common bugfix
- [T229]                    Minor update / common bugfix
- [T244]                    Minor update / common bugfix
- [T246]                    Minor update / common bugfix
- [T248]                    Minor update / common bugfix
- [T249]                    Minor update / common bugfix
- [T250]                    Minor update / common bugfix
- [T261]                    Minor update / common bugfix
- [T263]                    Minor update / common bugfix
- [T258]                    Minor update / common bugfix
- [T214]                    Minor update / common bugfix
- [T210]                    Minor update / common bugfix
- [T222]                    Minor update / common bugfix
- [T231]                    Minor update / common bugfix
- [T252]                    Minor update / common bugfix
- [T254]                    Minor update / common bugfix
- [T257]                    Minor update / common bugfix
- [T251]                    Minor update / common bugfix
- [T213]                    Minor update / common bugfix

----------------------------------------------------------------------------------------------------
# 2.6
- [T1]                      Using tags for all object types
- [T206]                    Scripts (Linux/Debian and Windows) for updating the meta-grid-installer-updater
----------------------------------------------------------------------------------------------------
# 2.5.1
- [T198]                    Ready for PHP 8.1.2
- [T204]                    Don't show unused fields
- [T199]                    Easy Demo Login
- [T189]                    Bugfix
----------------------------------------------------------------------------------------------------
# 2.5
- [T81 | GitHub Issue #4]   Bugfix -> User can't be deleted
- [T157]                    Yii2 framework update 2.0.12 to 2.0.43
- [T175]                    Bugfix
- [T185]                    Bugfix
- [T188]                    Support higher PHP versions (up to PHP 7.4)
----------------------------------------------------------------------------------------------------
# 2.4.5
- [T182]
- [T181]
----------------------------------------------------------------------------------------------------
# 2.4.4
- [T179]
----------------------------------------------------------------------------------------------------
# 2.4.3
- [T178]
- [T166]
- [T144]
- [T152]
- [T153]
- [T176]
- [T171]
- [T154]
- [T170]
- [T168]
- [T167]
- [T151]
- [T158]
----------------------------------------------------------------------------------------------------
# 2.4.2

- [T146]                    Bugfix -> Out-of-memory exception (mapping dialog / new app_config parameter)
- [T145]                    Show available meta#grid update on start page
- [T143]                    Typo correction
- [T142]                    Bugfix -> LiquiBase output only in English language (German Windows leads to localized text outputs) (meta#grid update tool)
- [T141]                    Bugfix -> Out-of-memory exception (copy & paste import / new app_config parameter)
- [T139]                    Bugfix -> Out-of-memory and timeout exception (copy & paste import / new app_config parameter)
- [T138]                    Optimizing performance to display of views (e.g. db_table)
- [T137]                    Changed search to be emtpy on entry (faster page building)
- [T136]                    Clickable links to db_table objects in bracket list
- [T135]                    Keep filter in index view after deleting of elements
- [T133]                    Bugfix -> Timeout exception (copy & paste import)
- [T132]                    Options to import and delete in one action (copy & paste import)
- [T129]                    Bugfix -> Perpective didn't filter all elements
- [T127]                    Show more information in mapping list of objects
- [T91]                     Jump to choosen db_table_field (in detail view) from index view
----------------------------------------------------------------------------------------------------

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
