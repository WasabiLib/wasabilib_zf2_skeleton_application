<?php
/**
 * Created by PhpStorm.
 * User: sascha.qualitz
 * Date: 02.12.14
 * Time: 13:03
 */
namespace ComposerScript;

use Composer\Script\Event;

class Installer
{
    public static function postUpdate(Event $event)
    {
        echo "postUpdate";
        self::preInstall();
    }

    public static function postInstall(Event $event)
    {
        self::preInstall();

        $answer = self::getUserInput("Would you like to create your own repository for your new project? (y/n): ");
        if($answer && $answer === "y") {
            self::rrmdir('.git');
            $output = `git init`;
            $output = `git add -A`;
            $output = `git commit -m"Initialer Commit"`;

            $gitOrigin = self::getUserInput("Please enter the path to the remote repository (or press enter):");
            if($gitOrigin) {
                $output = `git remote add origin $gitOrigin`;
            }
            echo $output;
        }
    }

    private static function getUserInput($questionText) {
        if (PHP_OS == 'WINNT') {
            echo $questionText;
            $answer = stream_get_line(STDIN, 1024, PHP_EOL);
        } else {
            $answer = readline($questionText);
        }

        return $answer;
    }

    private static function preInstall() {
            rename('vendor/wasabi/wasabilib', 'vendor/WasabiLib');
            rename('vendor/wasabi/wasabimail', 'vendor/WasabiMail');
            rmdir('vendor/wasabi');


            self::rcopy("vendor/WasabiLib/wasabi", "public/vendor/wasabi");
    }

    // copies files and non-empty directories
    private static function rcopy($src, $dst) {
        if (file_exists($dst)) {
            self::rrmdir($dst);
        }
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    self::rcopy("$src/$file", "$dst/$file");
                }
            }
        }
        else if (file_exists($src)) {
            copy($src, $dst);
        }
    }
}