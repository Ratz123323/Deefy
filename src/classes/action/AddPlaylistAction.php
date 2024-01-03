<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action
{

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if($_SERVER['REQUEST_METHOD']==='GET'){
            $contenuHTML .= <<<COUCOU
                            <form method="post" action="?action=add-playlist">
                            <fieldset>
                                <legend> Ajouter une nouvelle Playlist </legend>
                                <input type="text" name="nomPlaylist" placeholder="Nom de la nouvelle playlist" required>
                                <button type="submit" name="ajouter_playlist" value="ajouter_p1">Ajouter</button>
                            </fieldset>
                            COUCOU;
        }else{ // POST
            // Lors de la validation du formulaire, instancier une PlayList avec le nom saisi (et nettoyé)

            // On nettoye le nom de la playlist
            $nomNettoye = filter_var($_POST['nomPlaylist'], FILTER_SANITIZE_STRING);

            // On créé le nouvelle playlist (sans pistes) avec le nom nettoyé
            $playlist = new Playlist($nomNettoye, array());

            // On l'ajoute dans le tableau de session
            $_SESSION["playlist"][] = $playlist;

            // On affiche la playlist
            $affichage = new AudioListRenderer($playlist);

            try {
                $contenuHTML .= $affichage->render(1);
            } catch (InvalidPropertyNameException $e) {
                print $e->getMessage();
            }

            $contenuHTML .= "<a href=\"?action=add-podcasttrack\"><button id=\"ajouter\">Ajouter une piste</button></a>";
        }

        return $contenuHTML;
    }
}