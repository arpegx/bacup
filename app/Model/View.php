<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

class View
{
    private static string $views = "./app/View/";

    public static function make(string $view, array $data = [])
    {

        //. source view
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