<?php namespace NSRosenqvist\TwigColorTools;

class Plugin extends \System\Classes\PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Twig Color Tools',
            'description' => 'A collection of Twig functions for working with colors',
            'author' => 'Niklas Rosenqvist',
            'icon' => 'icon-leaf',
            'homepage' => 'https://www.nsrosenqvist.com/'
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'color_lighten' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'lighten'],
                'color_darken' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'darken'],
                'color_red' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'red'],
                'color_green' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'green'],
                'color_blue' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'blue'],
                'color_alpha' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'alpha'],
                'color_mix' => ['\NSRosenqvist\TwigColorTools\Classes\ColorTools', 'mix'],
            ]
        ];
    }
}
