<?php namespace ProcessWire;

use Exception;

class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

class AutoInstaller {
    /**
     * @var \ProcessWire\Installer;
     */
    protected $installer;

    /**
     * Constructor
     */
    public function __construct()
    {
        if(is_file("./site/assets/installed.php")) {
            die("This installer has already run. Please delete it.");
        }
        $this->includeInstaller();
        $this->installer = new \ProcessWire\Installer();
    }

    /**
     * Runs the auto installer.
     */
    public function execute()
    {
        $this->executeStep(0);
        $this->executeStep(2);
        $this->executeStep(4);
        $this->executeStep(5);
        echo "\n";
        echo "==================================\n";
        echo "🎉 Your ProcessWire site is ready! 🎉\n";
        echo "==================================\n";
        echo "\n";
    }

    /**
     * Imports the ProcessWire installer.
     */
    public function includeInstaller()
    {
        ob_start();
        require_once("./install.php");
        ob_clean();
    }

    /**
     * Cleans up the $_POST global variable and sets the given list of variables into it.
     *
     * @param array $post The list of post variables.
     */
    public function setPostVars(array $post)
    {
        foreach ( $_POST as $name => $value ) {
            unset($_POST[$name]);
        }

        foreach ( $post as $name => $value) {
            $_POST[$name] = $value;
        }
    }

    /**
     * Executes the installlation step.
     *
     * @param int $step The installation step number.
     */
    public function executeStep(int $step)
    {
        $stepMethod = "executeStep$step";
        ob_start();
        $this->$stepMethod();
        ob_clean();
        echo "Step $step complete! ✅\n";
    }

    public function executeStep0()
    {
        $this->setPostVars([
            'step' => '0',
            'profile' => getenv('SITE_PROFILE'),
        ]);
        putenv('HTTP_MOD_REWRITE=On');
        $this->installer->execute();
    }

    public function executeStep2()
    {
        $this->setPostVars([
            'step' => '2',
        ]);
        $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
        $_SERVER['SERVER_NAME'] = getenv('HTTP_HOST');
        $this->installer->execute();
    }

    public function executeStep4()
    {
        $this->setPostVars([
            'step' => '4',
            "dbName"	=> getenv('DB_NAME'),
            "dbUser"	=> getenv('DB_USER'),
            "dbPass"	=> getenv('DB_PASS'),
            "dbHost"	=> getenv('PWUP_PREFIX') . "db",
            "dbPort"	=> "3306",
            "dbCharset"	=> getenv('DB_CHARSET'),
            "dbEngine"	=> getenv('DB_ENGINE'),
            "timezone"	=> getenv('TIMEZONE'),
            "chmodDir"	=> getenv('CHMOD_DIR'),
            "httpHosts" => getenv('HTTP_HOST'),
            "chmodFile"	=> getenv('CHMOD_FILE'),
            "debugMode"	=> getenv('DEBUG_MODE'),
            "dbTablesAction" => getenv('DB_EXISTING_TABLES_ACTION'),
        ]);
        $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
        $_SERVER['SERVER_NAME'] = getenv('HTTP_HOST');
        $this->installer->execute();
    }

    public function executeStep5()
    {
        $this->setPostVars([
            "admin_name"       => getenv("ADMIN_PATH_NAME"),
            "username"         => getenv("ADMIN_USERNAME"),
            "userpass"         => getenv("ADMIN_USERPASS"),
            "userpass_confirm" => getenv("ADMIN_USERPASS"),
            "useremail"        => getenv("ADMIN_USEREMAIL"),
            "remove_items"     => ['install-php', 'install-dir', 'site-blank'],
            "step"             => '5',
        ]);
        $this->installer->execute();
    }
}

error_reporting(E_ALL | E_STRICT); 
(new DotEnv(__DIR__ . '/.env'))->load();
$autoinstaller = new AutoInstaller();
$autoinstaller->execute();

