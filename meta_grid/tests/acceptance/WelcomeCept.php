<?php 

/**
 * This acceptance test will step to many Meta#Grid object types, create an item and delete it again via GUI.
 * The "deletion-process" in Yii2 will prompt for an use choice, if the deletion really shall be done.
 *    Is is only works (is testable) with the Webdriver/Selenium because it can handle JavaScript browser prompts.
 * 
 * Procedure:
 *   1. (otional) Try to login with an wrong admin password
 *   2. Login as admin
 *   3. Run function get_all_view_forms() to get all objects to test
 *   4. Create an item
 *   5. Check, if item can be seen in the index-GridView-view
 *   6. Delete item via delete action
 *   7. Check, if item can be seen anymore
 */

echo "**********************************\n";
echo getcwd();
echo "**********************************\n";


$I = new AcceptanceTester($scenario);

$I->wantTo('ensure that login works');
// $I->amOnPage("http://localhost/meta_grid/index.php");
$I->amOnPage("/");  // Webdriver
$I->see("The data catalog for everybody!");

$username="admin";
$password="admin";

// $I->wantTo('Try to login as "admin" with a wrong password');
$I->click('Login');
// $I->fillField('input[name="LoginForm[login]"]', $username); // LoginForm[login]
// $I->fillField('input[name="LoginForm[password]"]', $password."WRONG"); // LoginForm[password]
// $I->click('Sign in');
// $I->wantTo('See the error message');
// $I->see('Invalid login or password');

$I->wantTo('Try to login as "admin" with a correct password');
$I->fillField('input[name="LoginForm[login]"]', $username); // LoginForm[login]
$I->fillField('input[name="LoginForm[password]"]', $password); // LoginForm[password]
$I->click('Sign in');

if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}

$I->wantTo('We want to see the username "admin" and the breadcrump "Start Page  "');
$I->see('admin');
$I->see('Start Page');

// $I->wantTo('Switching to the user administration/assignments');
// $I->fillField('input[class=".selectize-input"]', "mgadmin"); // 


// $I->wantTo('Create an ETL Job');
// // $I->amOnPage("http://localhost/meta_grid/index.php?r=datatransferprocess%2Fcreate");
// $I->amOnPage("index.php?r=datatransferprocess/create"); // wenn in acceptance.suite.yml -> url: http://localhost/meta_grid
// $I->fillField('input[name="DataTransferProcess[name]"]', "ETL Job from Codeception");
// $I->click('Create');
// $I->wantTo('Delete the created ETL Job on index-view (GridView)');
// // $I->amOnPage("http://localhost/meta_grid/index.php?r=datatransferprocess");
// $I->amOnPage("index.php?r=datatransferprocess");
// // https://github.com/Codeception/Codeception/issues/467
// // $I->click('//a[@href[contains(.,"delete&id=10")]]'); // Klappt auch sofern 10 vorhanden ist
// $I->click('(//a[@href[contains(.,"delete&id")]])[last()]'); // letztes Element
// $I->acceptPopup();
// if (method_exists($I, 'wait')) {
//     $I->wait(1); // only for selenium
// }
// $I->scrollTo('(//a)[last()]');
// if (method_exists($I, 'wait')) {
//     $I->wait(1); // only for selenium
// }

/**
 * Wait for given seconds. Copied from codeception examples.
 */
function selenium_wait($I, $seconds=1)
{
    if (method_exists($I, 'wait')) {
        $I->wait($seconds); // only for selenium
    }
}

/**
 * Repeatable scenario. Create and delete via GUI.
 */
function create_and_delete($I, $object_type_name, $object_type_const, $fieldname = 'name', $fieldtype='input')
{
    $datetime = new DateTime();
    $datetimeISO = $datetime->format(DateTime::ATOM);
    $I->wantTo('Create a ' . $object_type_name);
    $I->amOnPage("index.php?r=" . strtolower($object_type_const) . "/create"); // wenn in acceptance.suite.yml -> url: http://localhost/meta_grid
    $newItemName = "$object_type_const from Codeception [$datetimeISO]";
    $I->fillField($fieldtype.'[name="' . $object_type_const . '['.$fieldname.']"]', $newItemName);
    $I->click('Create');
    selenium_wait($I, 1);
    $I->wantTo('Delete the newly created ' . $object_type_name . ' on index-view (GridView)');
    $I->amOnPage("index.php?r=".strtolower($object_type_const));
    $I->scrollTo('(//a)[last()]');
    $I->see($newItemName); // Check if created
    selenium_wait($I, 5);
    $I->click('(//a[@href[contains(.,"delete&id")]])[last()]'); // letztes Element
    $I->acceptPopup();
    $I->scrollTo('(//a)[last()]');
    selenium_wait($I, 2);
    $I->dontSee($newItemName); // Check if deleted
    selenium_wait($I, 1);
}

// Copied from /opt/meta_grid/frontend/vendor/yiisoft/yii2/helpers/BaseInflector.php
function encoding()
{
    return 'UTF-8';
}

// Copied from /opt/meta_grid/frontend/vendor/yiisoft/yii2/helpers/BaseStringHelper.php
function mb_ucfirst($string, $encoding = 'UTF-8')
{
    $firstChar = mb_substr((string)$string, 0, 1, $encoding);
    $rest = mb_substr((string)$string, 1, null, $encoding);

    return mb_strtoupper($firstChar, $encoding) . $rest;
}

// Copied from /opt/meta_grid/frontend/vendor/yiisoft/yii2/helpers/BaseStringHelper.php
function mb_ucwords($string, $encoding = 'UTF-8')
{
    $string = (string) $string;
    if (empty($string)) {
        return $string;
    }

    $parts = preg_split('/(\s+\W+\s+|^\W+\s+|\s+)/u', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $ucfirstEven = trim(mb_substr($parts[0], -1, 1, $encoding)) === '';
    foreach ($parts as $key => $value) {
        $isEven = (bool)($key % 2);
        if ($ucfirstEven === $isEven) {
            $parts[$key] = mb_ucfirst($value, $encoding);
        }
    }

    return implode('', $parts);
}

// Copied (and modified a bit for the function encoding) from /opt/meta_grid/frontend/vendor/yiisoft/yii2/helpers/BaseInflector.php
// Inspired from function getAllControllers in /opt/meta_grid/frontend/vendor/meta_grid/helper/Utils.php
function id2camel($id, $separator = '-')
{
    if (empty($id)) {
        return (string) $id;
    }
    return str_replace(' ', '', mb_ucwords(str_replace($separator, ' ', $id), encoding()));
}

/**
 * Get all objects to test.
 * Run through each directory of the given parameter $path.
 * If a file named _form.php is found, then look into the file and extract the "id" of the object type.
 * This is the only place, where to get the templates for the naming convention (outside of the Yii2 framework).
 * The extracted "id" is separated with "-". It can and will be converted to CamelCase version.
 * The view returns an array for further processing.
 */
function get_all_view_forms($path='../../views/')
{
    $results = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($rii as $file) {
        if ($file->isDir()){ 
            continue;
        }
            
        $fname = $file->getFilename();
        if ($fname === '_form.php')
        {
            foreach(file($file->getPathname()) as $line) {
                // EXAMPLE: '<div class="data-transfer-process-form">'
                $find1 = '<div class="';
                $find2 = '-form">';
                if ((strpos($line, $find1) !== false) && (strpos($line, $find2) !== false))
                {
                    $line = str_replace("\n", "", $line); // replace newline
                    $line = str_replace("\r", "", $line); // replace carriage return
                    $id = str_replace($find1, '', $line); // replace '<div class="'
                    $id = str_replace($find2, '', $id);   // replace '-form">'
                    $id_camel = id2camel($id);
                    // print $file->getPathname() . "|" . $fname  . "|" . $line . "|" . $id_camel . "\n";
                    $results[$id_camel] = $file->getPathname();
                }
            }
        }
    }
    return $results;
}


foreach(get_all_view_forms($path='./views/') as $object_type_const=>$filepath)
{
    $field_name="name";
    $object_type_name = $object_type_const;

    if ($object_type_const === "Contact") $field_name="givenname";
    if ($object_type_const === "Tool") $field_name="tool_name";

    if ($object_type_const === "ObjectDependsOn") continue; // ref_fk_object_id_parent must be an int. Skipping this for now.
    if ($object_type_const === "MapObject2Object") continue; // does not exists. It's named "mapper". Mapper need four int inputs to be created. Skipping this for now.
    if ($object_type_const === "Objectcomment") continue; // Redactor textedit is not selectable and ref_fk_object_id must be an int. Skipping this for now
    if ($object_type_const === "DbTable") continue; // will be forwarded to Dbtablefieldmultiple
    if ($object_type_const === "Bracket") continue; // need other config (pattern can not be empty)
    if ($object_type_const === "Dbtablefieldmultiple") continue; // need other config (first field can not be empty)
    if ($object_type_const === "DbTableField") continue; // need other config (more than one page in index-GridView-view)

    if ($object_type_const === "ImportStageDbTable") continue; // ignore
    if ($object_type_const === "VallObjectsUnion") continue; // ignore
    create_and_delete($I=$I, $object_type_name=$object_type_name, $object_type_const=$object_type_const, $field_name=$field_name);
}

// EXAMPLE:
// $object_type_name = "DataTransferProcess";
// $object_type_const = "DataTransferProcess";
// $object_type_name = "Contact";
// $object_type_const = "Contact";
// $field_name="givenname
// create_and_delete($I=$I, $object_type_name=$object_type_name, $object_type_const=$object_type_const,$fieldname=$field_name, $fieldtype=$fieldtype);

// run selenium with: java -jar /home/$USER/Downloads/selenium-server-4.15.0.jar  standalone
// execute with: cd /opt/meta_grid/frontend; vendor/bin/codecept run acceptance