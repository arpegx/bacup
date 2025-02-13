<?php declare(strict_types=1);

namespace Arpgex\Bacup\Model;

class Configuration
{
    private static ?Configuration $instance = null;
    const XML_DEFAULT = "data/default.xml";
    const XSD_SCHEMA = "data/schema.xsd";
    public readonly string $PATH;
    public readonly string $FILE;

    private function __construct()
    {
        $this->PATH = $_ENV["HOME"] . "/.config/bacup/";
        $this->FILE = $this->PATH . "config.xml";
    }

    public static function getInstance()
    {
        return self::$instance ??= new Configuration();
    }

    public function create()
    {
        //repeated call
        if (file_exists($this->FILE)) {
            print "Configuration exists already\n";
            return 0;
        }

        // setup configuration
        mkdir($this->PATH, 0700, true);
        file_put_contents(
            $this->FILE,
            file_get_contents(self::XML_DEFAULT)
        );

        print "Configuration established\n";
    }
}