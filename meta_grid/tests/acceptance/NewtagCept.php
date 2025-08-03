<?php 

/**
 * 1. Login as admin user
 * 2. Create a new Url object
 * 3. Add a new tag to the new Url object
 * 4. Check if tag was added
 */



$I = new AcceptanceTester($scenario);

$I->wantTo('Do login');
$I->amOnPage("/");  // Webdriver
$I->see("The data catalog for everybody!");

$username="admin";
$password="admin";

$I->click('Login');
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

function selenium_wait($I, $seconds=1)
{
    if (method_exists($I, 'wait')) {
        $I->wait($seconds); // only for selenium
    }
}

/**
 * Create a tag in object_type url.
 */
$datetime = new DateTime();
$datetimeISO = $datetime->format(DateTime::ATOM);
$I->wantTo('Create a object item for a tag');
$I->amOnPage("index.php?r=url/create"); // wenn in acceptance.suite.yml -> url: http://localhost/meta_grid
$newItemName = "Url from Codeception [$datetimeISO]";
$I->fillField('input[name="' . "Url" . '['."name".']"]', $newItemName);
$I->click('Create');
selenium_wait($I, 2);
$I->wantTo('Add a new tag');
$I->fillField('/html/body/div/div[1]/div[2]/div/table/tbody/tr[1]/td/div/div[1]/input', "New tag for ".$newItemName);
// https://codeception.com/docs/modules/WebDriver
$I->pressKey('/html/body/div/div[1]/div[2]/div/table/tbody/tr[1]/td/div/div[1]/input','',\Facebook\WebDriver\WebDriverKeys::RETURN_KEY); //=> new
selenium_wait($I, 1);
$I->reloadPage();
selenium_wait($I, 1);
$I->wantTo('Check if new tag is visible');
$I->see("New tag for ".$newItemName); // Check if created
selenium_wait($I, 5);



// run selenium with: java -jar /home/$USER/Downloads/selenium-server-4.15.0.jar  standalone
// execute with: cd /opt/meta_grid/frontend; vendor/bin/codecept run acceptance NewtagCept.php