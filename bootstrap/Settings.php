<?php

namespace Bootstrap;

use Dotenv\Dotenv;

class Settings
{
    /**
     * Dotenv instance
     * @var \Dotenv\Dotenv
     */
    protected $dotenv;

    /**
     * Application settings
     * @var array
     */
    protected $config = [];

    /**
     * Instantiate Dotenv
     */
    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();

        $this->dotenv = $dotenv;

        $this->configure();
    }

    /**
     * Get configuration
     * @return array application config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set a key on application config
     * @param string $key    configuration key
     * @param array  $config configuration data
     */
    protected function setConfig($data)
    {
        $this->config = array_merge($this->getConfig(), $data);
    }

    /**
     * Configure the application variables
     * @return void
     */
    public function configure()
    {
        $this->configureEnvironment();
        $this->configureSlim();
        $this->configureDomain();
        $this->configureLocale();
        $this->configureTimezone();
        $this->configureDatabase();
        $this->configureMailer();
        $this->configureDeployd();
        $this->configureTwig();
        $this->configureFacebook();
    }

    /**
     * Configure environment
     * @return void
     */
    public function configureEnvironment()
    {
        $config = [];

        $this->dotenv->required('ENVIRONMENT')->allowedValues(['development', 'production']);

        $environment = getenv('ENVIRONMENT');

        $config['environment'] = $environment;

        if ($environment == 'development') {
            $config['displayErrorDetails'] = true;

            ini_set("display_errors", 1);
            ini_set("html_errors", 1);
            error_reporting(E_ALL);
        } else {
            $config['displayErrorDetails'] = false;

            ini_set("display_errors", 0);
            ini_set("html_errors", 0);
            error_reporting(0);
        }

        $this->setConfig($config);
    }

    /**
     * Configure Slim (slim internal settings)
     * @return void
     */
    public function configureSlim()
    {
        $config = [];

        $config['determineRouteBeforeAppMiddleware'] = true;
        $config['addContentLengthHeader'] = true;

        $this->setConfig($config);
    }

    /**
     * Configure application domain
     * @return void
     */
    public function configureDomain()
    {
        $config = [];

        $config['domain'] = getenv('DOMAIN');

        $this->setConfig($config);
    }

    /**
     * Configure locale
     * @return void
     */
    public function configureLocale()
    {
        $locale         = getenv('LOCALE');
        $localeFallback = getenv('LOCALE_FALLBACK');

        $config['locale']         = $locale;
        $config['localeFallback'] = $localeFallback;

        if (!empty($locale)) {
            setlocale(LC_ALL, $locale);
        } else {
            setlocale(LC_ALL, $localeFallback);
        }

        $this->setConfig($config);
    }

    /**
     * Configure timezone
     * @return void
     */
    public function configureTimezone()
    {
        $timezone = getenv('TIMEZONE');

        $config['timezone'] = $timezone;

        if (!empty($timezone)) {
            date_default_timezone_set($timezone);
        }

        $this->setConfig($config);
    }

    /**
     * Configure database
     * @return void
     */
    public function configureDatabase()
    {
        $config = [];

        $config['db']['dbms']     = getenv('DB_DBMS');
        $config['db']['host']     = getenv('DB_HOST');
        $config['db']['username'] = getenv('DB_USERNAME');
        $config['db']['password'] = getenv('DB_PASSWORD');
        $config['db']['database'] = getenv('DB_DATABASE');

        $this->setConfig($config);
    }

    /**
     * Configure Mailer (PHPMailer)
     * @return void
     */
    public function configureMailer()
    {
        $config = [];

        $config['mail']['smtp']['host']     = getenv('MAIL_SMTP_HOST');
        $config['mail']['smtp']['user']     = getenv('MAIL_SMTP_USER');
        $config['mail']['smtp']['password'] = getenv('MAIL_SMTP_PASSWORD');
        $config['mail']['smtp']['protocol'] = getenv('MAIL_SMTP_PROTOCOL');
        $config['mail']['smtp']['port']     = getenv('MAIL_SMTP_PORT');

        $this->setConfig($config);
    }

    /**
     * Configure Deployd
     * @return void
     */
    public function configureDeployd()
    {
        $config = [];

        $config['dpd']['host']      = getenv('DPD_HOST');
        $config['dpd']['protocol']  = getenv('DPD_PROTOCOL');

        $this->setConfig($config);
    }

    /**
     * Configure Twig
     * @return void
     */
    public function configureTwig()
    {
        $config = [];

        $config['twig']['templates']['path']    = getenv('TWIG_TEMPLATES_PATH');
        $config['twig']['cache']['path']        = getenv('TWIG_CACHE_PATH');
        $config['twig']['public']               = getenv('TWIG_PUBLIC');

        $config['assets']['path']               = getenv('ASSETS_PATH');
        $config['assets']['chmod']              = getenv('ASSETS_CHMOD');
        $config['assets']['base']               = getenv('ASSETS_BASE');
        $config['assets']['cache']              = getenv('ASSETS_CACHE');
        $config['assets']['name']               = getenv('ASSETS_NAME');
        $config['assets']['lifetime']           = getenv('ASSETS_LIFETIME');
        $config['assets']['minify']             = getenv('ASSETS_MINIFY');

        $this->setConfig($config);
    }

    /**
     * Configure Facebook
     * @return void
     */
    public function configureFacebook()
    {
        $config = [];

        $config['facebook']['app']['id']        = getenv('FACEBOOK_APP_ID');
        $config['facebook']['app']['secret']    = getenv('FACEBOOK_APP_SECRET');
        $config['facebook']['token']            = getenv('FACEBOOK_TOKEN');

        $this->setConfig($config);
    }
}
