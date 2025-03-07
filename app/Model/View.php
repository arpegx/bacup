<?php declare(strict_types=1);

namespace Arpegx\Bacup\Model;

class View
{
    private static string $views = "./app/View/";

    public static function make(string $view, array $data = [])
    {
        $file = realpath(self::$views . $view . ".html");
        $content = file_get_contents($file);

        $output = $content;
        foreach ($data as $key => $value) {
            $output = str_replace("{\${$key}}", $value, $output);
        }

        $banner = file_get_contents(realpath(self::$views . "banner.html"));

        return "<div>" . $banner . $output . "</div>";
    }
}