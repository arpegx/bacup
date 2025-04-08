<?php

declare(strict_types=1);

namespace Arpgex\Bacup\Model;

use DOMXPath;
use Webmozart\Assert\Assert;

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
     * Summary of xpath
     * @var \DOMXPath
     */
    public DOMXPath $xpath;

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

        // schema validation
        Assert::true($this->configuration->schemaValidate(self::XSD_SCHEMA), "Invalid configuration format.");

        // \DOMXpath
        $this->xpath = new DOMXPath($this->configuration);
        $this->xpath->registerNamespace("bac", "https://www.arpegx.com");
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
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return static
     */
    public function create()
    {
        mkdir($this->PATH, 0700, true);
        Assert::directory($this->PATH, "Failed to create directory " . $this->PATH);

        return $this;
    }

    public function add(array $data)
    {
        $item = $this->configuration->createElementNS(qualifiedName: "item", namespace: "https://www.arpegx.com");
        $item->setAttribute('id', uniqid());

        $source = $this->configuration->createElementNS(qualifiedName: "source", value: $data["target"], namespace: "https://www.arpegx.com");
        $item->appendChild($source);

        $parameters = $this->configuration->createElementNS(qualifiedName: "parameters", value: "parameters", namespace: "https://www.arpegx.com");
        $item->appendChild($parameters);

        $this->configuration->firstElementChild->insertBefore($item);

        return $this;
    }

    /**
     *. save virtual dom to configuration file
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return void
     */
    public function save()
    {
        Assert::true($this->configuration->schemaValidate(self::XSD_SCHEMA), "Schemata Validation failed");
        file_put_contents($this->FILE, $this->configuration->saveXML());
    }

    /**
     *. checks if configuration is initialized
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->FILE);
    }

    /**
     *. Convert DOMNodeList into Array
     * @param \DOMNodeList $list
     * @param string|null $column filter option
     * @return array converted DOMNodeList
     */
    public function toArray(?\DOMNodeList $list = null, ?string $column = null)
    {
        $list ??= $this->xpath->query('bac:item/bac:source | bac:item/bac:parameters');

        $arr = array();

        foreach ($list as $node) {
            switch ($node->nodeName) {
                case "item":
                    foreach ($node->childNodes as $element) {
                        $arr[$element->parentElement->id][$element->nodeName] = $element->nodeValue;
                    }
                    break;
                default:
                    $arr[$node->parentElement->id][$node->nodeName] = $node->nodeValue;
                    break;
            }
        }

        if ($column !== null) {
            $arr = array_column($arr, $column);
        }

        return $arr;
    }

    public function remove() {}
}
