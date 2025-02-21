<?php declare(strict_types=1);

namespace Arpgex\Bacup\Model;

class Configuration
{
    /**
     *. instance of configuration
     * @var 
     */
    private static ?Configuration $instance = null;

    /**
     *. configuration as virtual dom
     * @var \DOMDocument
     */
    private \DOMDocument $configuration;

    /**
     *. default xml to bootstrap the configuration
     * @var string
     */
    const XML_DEFAULT = "data/default.xml";

    /**
     *. XSD Schemata to validate a configuration
     * @var string
     */
    const XSD_SCHEMA = "data/schema.xsd";

    /**
     *. path to configuration directory located at $HOME/.config/bacup
     * @var string
     */
    public readonly string $PATH;

    /**
     *. configuration file config.xml
     * @var string
     */
    public readonly string $FILE;

    /**
     *. ctor
     */
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

    /**
     *. instance of configuration
     * @return Configuration
     */
    public static function getInstance()
    {
        return self::$instance ??= new Configuration();
    }

    /**
     *. initialize configuration prerequisites
     * @return static
     */
    public function create()
    {
        try {
            mkdir($this->PATH, 0700, true);

        } catch (\Exception $e) {
            throw new \Exception("Failed to create directory " . $this->PATH);
        }
        return $this;
    }

    /**
     *. save virtual dom to configuration file
     * @throws \Exception
     * @return void
     */
    public function save()
    {
        if ($this->configuration->schemaValidate(self::XSD_SCHEMA)) {
            file_put_contents(
                $this->FILE,
                $this->configuration->saveXML(),
            );
        } else {
            throw new \Exception("Schemata Validation failed");
        }
    }

    /**
     *. checks if configuration is initialized
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->FILE);
    }
}