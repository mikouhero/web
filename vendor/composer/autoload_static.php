<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit45298e594f45749c8faa47280890a1df
{
    public static $files = array (
        'ddc3cd2a04224f9638c5d0de6a69c7e3' => __DIR__ . '/..' . '/topthink/think-migration/src/config.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\migration\\' => 16,
            'think\\composer\\' => 15,
            'think\\' => 6,
        ),
        'g' => 
        array (
            'gmars\\rbac\\' => 11,
            'gmars\\nestedsets\\' => 17,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'P' => 
        array (
            'Phinx\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\migration\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-migration/src',
        ),
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/../..' . '/thinkphp/library/think',
        ),
        'gmars\\rbac\\' => 
        array (
            0 => __DIR__ . '/..' . '/gmars/tp5-rbac/src',
        ),
        'gmars\\nestedsets\\' => 
        array (
            0 => __DIR__ . '/..' . '/gmars/tp5-nestedsets/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
        'Phinx\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-migration/phinx/src/Phinx',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit45298e594f45749c8faa47280890a1df::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit45298e594f45749c8faa47280890a1df::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
