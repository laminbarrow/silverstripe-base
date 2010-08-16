<?php
/* 
 * 
All code covered by the BSD license located at http://silverstripe.org/bsd-license/
 */

/**
 * Build task that provides some commonly used functionality
 *
 * @author marcus
 */
abstract class SilverStripeBuildTask extends Task {
    protected $cleanupEnv = false;
	
	protected function configureEnvFile() {
		$envDir = dirname(dirname(dirname(__FILE__)));
		// fake the _ss_environment.php file for the moment
		$ssEnv = <<<TEXT
<?php
// Set the \$_FILE_MAPPING for running the test cases, it's basically a fake but useful
define('SS_ENVIRONMENT_TYPE', 'dev');
global \$_FILE_TO_URL_MAPPING;
\$_FILE_TO_URL_MAPPING['$envDir'] = 'http://localhost';
?>
TEXT;

		$envFile = dirname(dirname(__FILE__)).'/_ss_environment.php';
		$this->cleanupEnv = false;
		if (!file_exists($envFile)) {
			file_put_contents($envFile, $ssEnv);
			$this->cleanupEnv = true;
		}
	}

	protected function cleanEnv() {
		if ($this->cleanupEnv) {
			$envFile = dirname(dirname(__FILE__)).'/_ss_environment.php';
			if (file_exists($envFile)) {
				unlink($envFile);
			}
		}
	}

	protected function exec($cmd) {
		passthru($cmd, $return);
		if ($return != 0) {
			throw new BuildException("Command '$cmd' failed");
		}
	}
}