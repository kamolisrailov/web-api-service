<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit166b847dd495a409d8f2a77e8bca1af8
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit166b847dd495a409d8f2a77e8bca1af8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit166b847dd495a409d8f2a77e8bca1af8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit166b847dd495a409d8f2a77e8bca1af8::$classMap;

        }, null, ClassLoader::class);
    }
}
