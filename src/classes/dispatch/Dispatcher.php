<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddUserAction;

class Dispatcher
{

    private String $action;

    public function __construct()
    {
        if(!isset($_GET["action"]))
            $_GET["action"] = "default";
        $this->action = $_GET["action"];
    }

    public function run(): void{
        if($this->action){
            switch($this->action){
                case 'add-user':
                    $action = new AddUserAction();
                    self::renderPage($action->execute());
                    break;

                case 'add-playlist':
                    $action = new AddPlaylistAction();
                    self::renderPage($action->execute());
                    break;

                case 'add-podcasttrack':
                    self::renderPage((new \iutnc\deefy\action\AddPodcasttrackAction)->execute());
                    break;

                case 'signin':
                    self::renderPage((new \iutnc\deefy\action\SigninAction)->execute());
                    break;

                case 'display-playlist':
                    self::renderPage((new \iutnc\deefy\action\DisplayPlaylistAction)->execute());
                    break;

                default:
                    self::renderPage("Bienvenue !");
            }
        }else{
            self::renderPage("Bienvenue !");
        }
    }

    private function renderPage(string $html): void{
        echo <<<COUCOU
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Deefy</title>
                <style>
                    /* Styles pour le corps de la page */
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f0f0f0;
                        margin: 0;
                        padding: 0;
                    }
                    
                    /* Styles pour l'en-tête */
                    header {
                        background-color: #333;
                        color: #fff;
                        text-align: center;
                        padding: 10px;
                    }
                    
                    header h1{
                        font-size: 3em;
                        margin-top: 5px;
                        margin-bottom: 0px;
                    }
                    
                    /* Styles pour la section principale */
                    main {
                        max-width: 800px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                    }
                    
                    /* Styles pour les titres */
                    h1, h4 {
                        text-align: center;
                    }
                    
                    /* Styles pour les paragraphes */
                    p {
                        text-align: center;
                    }
                    
                    /* Styles pour les boutons */
                    button {
                        background-color: #f0f0f0;
                        color: #000000;
                        font-size: 20px;
                        border: none;
                        transition: all 0.3s ease;
                        border-radius: 5px;
                        padding: 10px 20px;
                        display: block;
                        margin: 0 auto;
                        text-decoration: none;
                    }
                    
                    button:hover {
                        background-color: #939393;
                    }
                    
                    button:active {
                        transform: scale(0.9);
                    }
                    
                    /* Styles pour les liens du menu de navigation */
                    header p {
                        margin: 10px;
                    }
                    
                    .menu-box {
                        display: flex; /* Pour disposer les éléments horizontalement */
                        justify-content: center;
                        border: 1px none #ccc; /* Bordure de la boîte */
                        border-radius: 5px; /* Coins arrondis de la boîte */
                        padding: 10px; /* Espacement interne de la boîte */
                        margin: 20px 0; /* Marge supérieure et inférieure de la boîte */
                    }
                    
                    /* Styles pour les éléments du menu */
                    .menu-box p {
                        margin-top: 0; /* Supprime les marges des éléments du menu */
                        margin-bottom: 0; /* Supprime les marges des éléments du menu */
                        text-align: center; /* Centre le texte à l'intérieur de chaque élément */
                    }
                    
                    .menu-box a {
                        text-decoration: none; /* Supprime la soulignement des liens */
                        color: #000; /* Couleur du texte des liens */
                        padding: 10px 20px; /* Espacement interne des liens (similaire aux boutons) */
                        border: 1px solid #ccc; /* Bordure autour des liens (similaire aux boutons) */
                        border-radius: 5px; /* Coins arrondis des liens (similaire aux boutons) */
                        background-color: #fff; /* Couleur de fond des liens */
                        transition: all 0.3s ease; /* Animation de survol des liens */
                    }
                    
                    .menu-box a:hover {
                        transform: scale(1.05); /* Agrandissement au survol des liens */
                        background-color: #ccc; /* Couleur de fond au survol des liens */
                    }

                    
                </style>
            </head>
            <body>
                <header>
                    <h1>Deefy</h1>
                    <div class="menu-box">
                        <p><a href="?">Accueil</a></p>
                        <p><a href="?action=add-user">Inscription</a></p>
                        <p><a href="?action=add-playlist">Créer une playlist</a></p>
                        <p><a href="?action=signin">Se connecter</a></p>
                    </div>
                </header>
                <main>
                    $html
                </main>
            </body>
            </html>
        COUCOU;
    }

}