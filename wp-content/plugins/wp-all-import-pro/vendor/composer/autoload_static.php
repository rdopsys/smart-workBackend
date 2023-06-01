<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ad073a7de8b1bb08996e6274fe48cad
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wpai\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wpai\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ad073a7de8b1bb08996e6274fe48cad::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ad073a7de8b1bb08996e6274fe48cad::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5ad073a7de8b1bb08996e6274fe48cad::$classMap;

        }, null, ClassLoader::class);
    }
}