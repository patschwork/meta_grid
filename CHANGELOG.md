# 2.4

- [T52] MultipleTableFieldEdit introduced: Object typed db_table and db_table_fields will now be added and edited in one view
- [T51] New attributes to flaf GDPR fields
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