<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

use Webmozart\Assert\Assert;

class View
{
    private static string $views = "./app/View/";

    public static function make(string $view, array $data = [])
    {
        /**
         * Do ...
         * check for variables (data, ? unique)
         * check for templates
         * check for base views
         * ? builder pattern
         * ? return View::object
         */

        //. source view
        Assert::fileExists(self::$views . $view . ".html", "View %s does not exist");
        $file = realpath(self::$views . $view . ".html");

        $output = file_get_contents($file);

        //. templates
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

        //. dynamic content
        foreach ($data as $key => $value) {
            $output = str_replace("{\${$key}}", $value, $output);
        }

        //. result
        return $output;
    }
}