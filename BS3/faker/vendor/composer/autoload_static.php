<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8adb7d67f3939c4909161387b2e6fb83
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Faker\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8adb7d67f3939c4909161387b2e6fb83::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8adb7d67f3939c4909161387b2e6fb83::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8adb7d67f3939c4909161387b2e6fb83::$classMap;

        }, null, ClassLoader::class);
    }
}
