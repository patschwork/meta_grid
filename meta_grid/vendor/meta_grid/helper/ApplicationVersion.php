<?php

namespace vendor\meta_grid\helper;

use Yii;

/**
 * Description of Helper
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 2.4
 * 
 */
class ApplicationVersion
{
    static $applicationVersion                 = "3.0.6";
    static $applicationName                    = "meta#grid";
    static $changelogfile_URL                  = "https://raw.githubusercontent.com/patschwork/meta_grid/master/CHANGELOG.md";
    static $cookie_name_changelog_md5          = "changelog_md5";
    static $cookie_name_new_version_available  = "new_version_available";

    public function getVersion()
    {
        return self::$applicationVersion;
    }    
    
    public function getApplicationName()
    {
        return self::$applicationName;
    }

    private function getContentOfChangelogFile()
    {
        /**
         * This function will get the content of the text file CHANGELOG.md from GitHub.
         * The file is used, for a later function to check, if an update is avaiable.
         * 
         * This is only done once per PHP session. The content will be stored as a session parameter.
         * 
         * If a netword related problem exists and the text file is not reachable, this is also flagged in a session parameter (and live while the session exists). 
         * This will avoid the application hanging until the timeout is reached (approx. 20 seconds).
         * 
         * @author meta#grid (Patrick Schmitz)
         * @since 3.0.6
         */
		$changelog_file_content = "";
        
        // T294 {...
        $changelog_file_content_from_session = \Yii::$app->session->get('user.changelog_file_content');
        if (! is_null($changelog_file_content_from_session))
        {
            return $changelog_file_content_from_session;
        }
        
        if (\Yii::$app->session->get('user.error_while_getting_changelog_file') == TRUE)
        {
            return "";
        }

		$changelog_file_url = self::$changelogfile_URL;

		$opts['https']['timeout'] = 1;
		if (version_compare(PHP_VERSION, '7.1.0', '>=')) 
        {
			$context = stream_context_create($opts);
			$file_headers =  @get_headers($changelog_file_url, 1, $context);
		}
        else 
        {
			$defaultOptions = stream_context_get_options(stream_context_get_default());
			stream_context_set_default($opts);
			$file_headers = @get_headers($changelog_file_url);
			stream_context_set_default($defaultOptions);
		}

		if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' ||trim($file_headers[0]) == 'HTTP/1.1 403 Forbidden')
        { $exists = false; }
        else { $exists = true; }
		// ...}

        if ($exists === true )
        {
			$changelog_file_content = file_get_contents($changelog_file_url); // file will be cached by PHP, so no need to store it locally when multiple quries
            \Yii::$app->session->set('user.error_while_getting_changelog_file', FALSE);
            \Yii::$app->session->set('user.changelog_file_content', $changelog_file_content);
		}
		else
		{
            \Yii::$app->session->set('user.error_while_getting_changelog_file', TRUE);
            Yii::warning($message = "Could not check for updates!", $category = 'meta_grid');
		}
				
		return $changelog_file_content;
	}

    // First check if file is different. If no, then no further investigation needed.
    private function isCachedMd5ChangelogDifferent()
    {
        $returnValue = false;
        $cached_md5 = self::getCookieChangelogHash();
        $remote_file = self::getContentOfChangelogFile();
        $changelog_content_md5 = md5($remote_file);
        if ($cached_md5 !== $changelog_content_md5)
        {
            $returnValue = true;
            self::setCookieChangelogHash($changelog_content_md5);
        }
        return $returnValue;
    }

    private function getStatusApplicationVersion()
    {
        $returnValue = VersionStatusComparedToCurrent::UNDEFINED;
        self::isCachedMd5ChangelogDifferent();
        if (1 == 1)
        {
            $changelog_file_content = self::getContentOfChangelogFile();
            $splitted_up = explode("# ",$changelog_file_content); // each markdown-header is a released version

            for ($i = 1; $i < count($splitted_up); $i++)
            {
                $versionToCheck = explode("\n",$splitted_up[$i])[0];
                if (version_compare($versionToCheck, self::$applicationVersion, '<'))
                {
                    $returnValue = VersionStatusComparedToCurrent::I_AM_NEWER;
                }
                else
                {
                    if (version_compare($versionToCheck, self::$applicationVersion, '='))
                    {
                        $returnValue = VersionStatusComparedToCurrent::EQUAL;
                        break;
                    }                       
                    if (version_compare($versionToCheck, self::$applicationVersion, '>'))
                    {
                        $returnValue = VersionStatusComparedToCurrent::I_AM_OLDER;
                        self::setCookieNewerVersion($versionToCheck);
                        break;
                    }                       
                }  
            }
        }
        return $returnValue;
    }
    public function isNewApplicationAvailable()
    {
        $returnValue = false; // no new version available

        if (self::getCookieNewerVersion() !== null) 
        {
            return true; // Fast path
        }
        if (self::getStatusApplicationVersion() === VersionStatusComparedToCurrent::I_AM_OLDER)
        {
            $returnValue = true;
        }
        return $returnValue;
    }

    private function setCookieChangelogHash($changelog_md5_val)
    {
        $cookie = new \yii\web\Cookie([
            'name' => self::$cookie_name_changelog_md5,
            'value' => $changelog_md5_val,
            'expire' => time() + 86400,
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
    }

    private function setCookieNewerVersion($newVersion)
    {
        $cookie = new \yii\web\Cookie([
            'name' => self::$cookie_name_new_version_available,
            'value' => $newVersion,
            'expire' => time() + 86400,
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
    }

    private function getCookieChangelogHash()
    {
        $returnValue = \Yii::$app->getRequest()->getCookies()->getValue(self::$cookie_name_changelog_md5);
        return $returnValue;
    }

    public function getCookieNewerVersion()
    {
        $returnValue = \Yii::$app->getRequest()->getCookies()->getValue(self::$cookie_name_new_version_available);
        return $returnValue;
    }
}

class VersionStatusComparedToCurrent {
    const UNDEFINED      = 0;
    const EQUAL          = 1;
    const I_AM_OLDER     = 2;
    const I_AM_NEWER     = 3;
    }