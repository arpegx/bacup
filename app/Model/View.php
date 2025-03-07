<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

class View
{
    private static string $views = "./app/View/";

    public static function make(string $view, array $data = [])
    {

        //. source view
        $file = realpath(self::$views . $view . ".html");
        $content = file_get_contents($file);
        $output = $content;

        //. templates
        // $banner = file_get_contents(realpath(self::$views . "banner.html"));
        // return "<div>" . $banner . $output . "</div>";

        if (str_contains($output, "@template")) {
            // return "true";
            $starts_at = strpos($output, "@template(\"");
            $ends_at = strpos(substr($output, $starts_at), "\")");

            $template_str = substr($output, $starts_at, $ends_at - $starts_at + 10) . PHP_EOL;
            $template = substr($output, $starts_at + 11, $ends_at - 3 - $starts_at);

            $template_replacement = file_get_contents(self::$views . $template . ".html");

            // echo "Variables \n";
            // var_dump(
            //     [
            //         $template_str,
            //         $template_replacement,
            //     ]
            // );

            $output = str_replace(
                $template_str,
                $template_replacement,
                $output
            );

            // echo "replaced \n";
            // var_dump($output);
            // echo "\n";
        }

        //. dynamic content
        foreach ($data as $key => $value) {
            $output = str_replace("{\${$key}}", $value, $output);
        }

        //. result
        return $output;
    }
}