<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

use Webmozart\Assert\Assert;
use function Termwind\render;

class IO
{
    private static string $views = "./app/View/";

    /**
     *. display view
     * @param string $view
     * @param array $data
     * @return void
     */
    public static function render(string $view, array $data = [])
    {
        render(IO::make($view, $data));
    }

    /**
     *. prepare view
     * @param string $view
     * @param array $data
     * @return string
     */
    public static function make(string $view, array $data = [])
    {
        $rawHTML = self::source($view);
        $templatedHTML = self::template($rawHTML);
        $output = self::datalize($templatedHTML, $data);

        return $output;
    }

    /**
     *. source html file
     * @param string $view
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return string|bool
     */
    private static function source(string $view)
    {
        Assert::fileExists($_view = self::$views . $view . ".html", "View %s does not exist");

        return file_get_contents(
            realpath($_view)
        );
    }

    /**
     *. replace template slots
     * @param string $html
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return string
     */
    private static function template(string $html)
    {
        if (str_contains($html, "@template")) {

            preg_match_all('/@template\("[a-z]+"\)/', $html, $matches);

            $templates = array_map(
                fn($match) => sscanf($match, '@template(" %[a-z]')[0],
                $matches[0]
            );

            array_walk($templates, function (&$template) use (&$html) {

                Assert::fileExists($_view = self::$views . $template . ".html", "Template %s is not existing.");
                $html = str_replace(
                    "@template(\"" . $template . "\")",
                    file_get_contents(realpath($_view)),
                    $html
                );
            });
        }
        return $html;
    }

    /**
     *. replace variable slots
     * @param string $html
     * @param array $data
     * @return string
     */
    private static function datalize(string $html, array $data)
    {
        foreach ($data as $key => $value) {
            $html = str_replace("{\${$key}}", (string) $value, $html);
        }
        return $html;
    }
}