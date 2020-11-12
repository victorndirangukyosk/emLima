<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit16ac7b2a6d76e27026d34df0f376e066
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rx\\' => 3,
            'React\\Promise\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rx\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit16ac7b2a6d76e27026d34df0f376e066::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit16ac7b2a6d76e27026d34df0f376e066::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
