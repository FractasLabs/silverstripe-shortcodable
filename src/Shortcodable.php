<?php

namespace Silverstripe\Shortcodable;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\View\Parsers\ShortcodeParser;

/**
 * Shortcodable
 * Manages shortcodable configuration and register shortcodable objects.
 *
 * @author shea@livesource.co.nz
 **/
class Shortcodable
{
    use Injectable;

    private static $shortcodable_classes = [];

    public static function register_classes($classes)
    {
        if (is_array($classes) && count($classes)) {
            foreach ($classes as $class) {
                self::register_class($class);
            }
        }
    }

    public static function register_class($class)
    {
        if (class_exists($class)) {
            if (!singleton($class)->hasMethod('parse_shortcode')) {
                user_error("Failed to register \"${class}\" with shortcodable. ${class} must have the method parse_shortcode(). See /shortcodable/README.md", E_USER_ERROR);
            }
            ShortcodeParser::get('default')->register($class, [$class, 'parse_shortcode']);
            singleton(ShortcodableParser::class)->register($class);
        }
    }

    public static function get_shortcodable_classes()
    {
        return Config::inst()->get(self::class, 'shortcodable_classes');
    }

    public static function get_shortcodable_classes_fordropdown()
    {
        $classList = self::get_shortcodable_classes();
        $classes = [];
        if (is_array($classList)) {
            foreach ($classList as $class) {
                if (singleton($class)->hasMethod('singular_name')) {
                    $classes[$class] = singleton($class)->singular_name();
                } else {
                    $classes[$class] = $class;
                }
            }
        }

        return $classes;
    }

    public static function get_shortcodable_classes_with_placeholders()
    {
        $classes = [];
        foreach (self::get_shortcodable_classes() as $class) {
            if (singleton($class)->hasMethod('getShortcodePlaceHolder')) {
                $classes[] = $class;
            }
        }

        return $classes;
    }
}
