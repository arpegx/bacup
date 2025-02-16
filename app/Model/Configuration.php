<?php declare(strict_types=1);

namespace Arpgex\Bacup\Model;

class Configuration
{
    private static ?Configuration $instance = null;
    private \DOMDocument $configuration;
    const XML_DEFAULT = "data/default.xml";
    const XSD_SCHEMA = "data/schema.xsd";
    public readonly string $PATH;
    public readonly string $FILE;

    private function __construct()
    {
        // filesystem
        $this->PATH = $_ENV["HOME"] . "/.config/bacup/";
        $this->FILE = $this->PATH . "config.xml";

        // \DomDocument
        $this->configuration = new \DOMDocument();
        $this->configuration->formatOutput = true;
        $this->configuration->preserveWhiteSpace = false;

        $this->exists()
            ? $this->configuration->load($this->FILE)
            : $this->configuration->load(self::XML_DEFAULT);

        $this->configuration->createAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'bac:attr',
        );
    }

    public static function getInstance()
    {
        return self::$instance ??= new Configuration();
    }

    public function create()
    {
        if ($this->configuration->schemaValidate(self::XSD_SCHEMA)) {
            // setup configuration
            mkdir($this->PATH, 0700, true);


            file_put_contents(
                $this->FILE,
                file_get_contents(self::XML_DEFAULT)
            );
        } else {
            throw new \Exception("Schemata Validation failed");
        }
    }

    public function exists()
    {
        return file_exists($this->FILE);
    }
}