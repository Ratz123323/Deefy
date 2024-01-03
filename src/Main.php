<?php

// import des classes necéssaires au bonn fonctionnement de l'application
/*
//Classes
require_once "AudioTrack.php";
require_once "AlbumTrack.php";
require_once "PodcastTrack.php";

//Renderers
require_once "Renderer.php";
require_once "AudioTrackRenderer.php";
require_once "PodcastTrackRenderer.php";
require_once "AlbumTrackRenderer.php";

//Exceptions
require_once "InvalidPropertyNameException.php";
require_once "NonEditablePropertyException.php";
require_once "InvalidPropertyValueException.php";
*/

// import de l'autoloader
require_once "../vendor/autoload.php";

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException;
use iutnc\deefy\render\AlbumTrackRenderer;
use iutnc\deefy\render\PodcastTrackRenderer;

try{
    //préparation des données
    $wooo = new AlbumTrack("Wooo", "../pistes_audio/104-vitalic-wooo.mp3");
    $auClairDeLaLune = new AlbumTrack("Au Clair de la Lune", "../pistes_audio/Clair_de_la_Lune_Tutti.mp3");

    //echo "TD4 Exercice 2 : ";
    //echo "<br>";
    //echo $wooo->__toString();
    //echo "<br>";
    //print($auClairDeLaLune->__toString());
    //
    //echo "<br>";
    //echo "<br>";

    echo "TD4 Exercice 3 : ";

    //préparation des données
    $w = new AlbumTrackRenderer($wooo);
    $a = new AlbumTrackRenderer($auClairDeLaLune);

    echo "<br>";
    print("Affichage court : ");
    echo "<br>";
    print $w->render(1);
    echo "<br>";
    print("Affichage long : ");
    echo "<br>";
    print $a->render(2);

    echo "<br>";
    echo "<br>";

    echo "TD4 Exercice 4 : ";

    //préparation des données
    $hypnose = new PodcastTrack("Hypnose", "../pistes_audio/Hypnose.mp3");
    $h = new PodcastTrackRenderer($hypnose);

    //echo "<br>";
    //echo $hypnose->__toString();
    echo "<br>";
    echo $h->render(2);

} catch (InvalidPropertyNameException $ipne) {
    print $ipne->getMessage();
} catch (NonEditablePropertyException $nepe) {
    print $nepe->getMessage();
} catch (InvalidPropertyValueException $ipve) {
    print $ipve->getMessage();
}
