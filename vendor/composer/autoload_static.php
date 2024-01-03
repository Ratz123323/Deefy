<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf39bcf6097977749961a128ec9c56fc1
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\deefy\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\deefy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'iutnc\\deefy\\audio\\lists\\Album' => __DIR__ . '/../..' . '/src/classes/audio/lists/Album.php',
        'iutnc\\deefy\\audio\\lists\\AudioList' => __DIR__ . '/../..' . '/src/classes/audio/lists/AudioList.php',
        'iutnc\\deefy\\audio\\lists\\Playlist' => __DIR__ . '/../..' . '/src/classes/audio/lists/Playlist.php',
        'iutnc\\deefy\\audio\\tracks\\AlbumTrack' => __DIR__ . '/../..' . '/src/classes/audio/tracks/AlbumTrack.php',
        'iutnc\\deefy\\audio\\tracks\\AudioTrack' => __DIR__ . '/../..' . '/src/classes/audio/tracks/AudioTrack.php',
        'iutnc\\deefy\\audio\\tracks\\PodcastTrack' => __DIR__ . '/../..' . '/src/classes/audio/tracks/PodcastTrack.php',
        'iutnc\\deefy\\exception\\InvalidPropertyNameException' => __DIR__ . '/../..' . '/src/classes/exception/InvalidPropertyNameException.php',
        'iutnc\\deefy\\exception\\InvalidPropertyValueException' => __DIR__ . '/../..' . '/src/classes/exception/InvalidPropertyValueException.php',
        'iutnc\\deefy\\exception\\NonEditablePropertyException' => __DIR__ . '/../..' . '/src/classes/exception/NonEditablePropertyException.php',
        'iutnc\\deefy\\render\\AlbumTrackRenderer' => __DIR__ . '/../..' . '/src/classes/render/AlbumTrackRenderer.php',
        'iutnc\\deefy\\render\\AudioListRenderer' => __DIR__ . '/../..' . '/src/classes/render/AudioListRenderer.php',
        'iutnc\\deefy\\render\\AudioTrackRenderer' => __DIR__ . '/../..' . '/src/classes/render/AudioTrackRenderer.php',
        'iutnc\\deefy\\render\\PodcastTrackRenderer' => __DIR__ . '/../..' . '/src/classes/render/PodcastTrackRenderer.php',
        'iutnc\\deefy\\render\\Renderer' => __DIR__ . '/../..' . '/src/classes/render/Renderer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf39bcf6097977749961a128ec9c56fc1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf39bcf6097977749961a128ec9c56fc1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf39bcf6097977749961a128ec9c56fc1::$classMap;

        }, null, ClassLoader::class);
    }
}