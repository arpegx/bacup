<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

use Webmozart\Assert\Assert;
use function Termwind\render;

class IO
{
    private static string $views = "./app/View/";

    public static function render(string $view, array $data = [])
    {
        render(IO::make($view, $data));
    }

    public static function make(string $view, array $data = [])
    {
        /**
         * Do ...
         * check for variables (data, ? unique)
         * check for templates
         * check for base views ( allow no infinite loop ? )
         * ? builder pattern
         * ? return View::object
         */

        //. source view
        $output = self::source($view);

        //. templates
        $output = self::template($output);

        //. dynamic content
        $output = self::datalize($output, $data);

        //. result
        return $output;
    }

    private static function source(string $view)
    {
        //. source view
        Assert::fileExists(self::$views . $view . ".html", "View %s does not exist");
        $file = realpath(self::$views . $view . ".html");

        return file_get_contents($file);
    }

    private static function template(string $output)
    {
        if (str_contains($output, "@template")) {

            preg_match_all('/@template\("[a-z]+"\)/', $output, $matches);

            $templates = array_map(
                fn($match) => sscanf($match, '@template(" %[a-z]')[0],
                $matches[0]
            );

            array_walk($templates, function (&$template) use (&$output) {

                Assert::fileExists(self::$views . $template . ".html", "Template %s is not existing.");
                $output = str_replace(
                    "@template(\"" . $template . "\")",
                    file_get_contents(self::$views . $template . ".html"),
                    $output
                );
            });
        }
        return $output;
    }

    private static function datalize(string $output, array $data)
    {
        foreach ($data as $key => $value) {
            $output = str_replace("{\${$key}}", $value, $output);
        }
        return $output;
    }
}