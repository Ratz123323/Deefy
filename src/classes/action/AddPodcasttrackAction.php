<?php

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcasttrackAction extends Action
{

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if($_SERVER['REQUEST_METHOD']==='GET'){
            $contenuHTML .= <<<COUCOU
                            <form method="post" action="?action=add-podcasttrack"  enctype="multipart/form-data">
                            <fieldset>
                                <legend> Ajouter une nouvelle Track </legend>
                                <input type="text" name="titre" placeholder="Titre de la Track" required>
                                <input type="text" name="genre" placeholder="Genre de la Track" required>
                                <input type="number" name="duree" placeholder="Duree de la Track" required>
                                <input type="text" name="auteur" placeholder="Auteur de la Track" required>
                                <input type="date" name="date" placeholder="Date du jour" required>
                                <input type="file" name="userfile" required>
                                <br>
                                <button type="submit" name="ajouter_playlist" value="ajouter_p1">Ajouter</button>
                            </fieldset>
                            COUCOU;
        }else{ // POST

            $Audiotrack = $_FILES["userfile"]["name"];
            $type = $_FILES["userfile"]["type"];
            $tmpName = $_FILES["userfile"]["tmp_name"];

            //vérification que c'est bien un .mp3
            if((substr($_FILES['userfile']['name'],-4) === '.mp3')&&($type === 'audio/mpeg')){

                // $titre à déplacer dans le dossier "pistes_audio" qui est à la racine (../audio)
                $targetDir = "../pistes_audio/"; // Répertoire de destination pour le fichier MP3
                $targetFile = $targetDir . $Audiotrack;
                move_uploaded_file($tmpName, $targetFile);

                //Création de la track
                try {
                    $track = new PodcastTrack($_POST["titre"], $targetFile);

                    //définition des attributs
                    $track->__set("auteur", filter_var($_POST['auteur'], FILTER_SANITIZE_STRING));
                    $track->__set("duree", filter_var(intval($_POST['duree']), FILTER_SANITIZE_NUMBER_INT));
                    $track->__set("genre", filter_var($_POST['genre'], FILTER_SANITIZE_STRING));
                    $track->__set("date", $_POST['date']);

                    //ajout de la track dans la playlist

                    if (!empty($_SESSION)){ // si il existe déjà des playlists

                        //var_dump($_SESSION);

                        //on récupère la playlist
                        $nomDeLaPlaylist = $_SESSION["playlist"][0]->__get("nom");
                        $pistes = $_SESSION["playlist"][0]->__get("tableauPistes");
                        $playlist = new Playlist($nomDeLaPlaylist, $pistes);

                        //on ajoute la nouvelle track
                        $playlist->ajouterPiste($track);

                        //on remets la playlist en session
                        $_SESSION["playlist"][0] = $playlist;

                        //on affiche la playlist avec la nouvelle track
                        $affichage = new AudioListRenderer($_SESSION["playlist"][0]);
                        $contenuHTML .= $affichage->render(1);

                    }else{
                        $contenuHTML .= "Vous ne posséder pas de playlist, veuillez en créer une : ";
                        $contenuHTML .= "<a href=\"?action=add-playlist\">Ajouter une playlist</a>";
                    }

                } catch (InvalidPropertyNameException $e) {
                    print($e->getMessage());
                } catch (NonEditablePropertyException $e1) {
                    print($e1->getMessage());
                } catch (InvalidPropertyValueException $e2) {
                    print($e2->getMessage());
                } catch (Exception $e3) {
                    print($e3->getMessage());
                }

                $contenuHTML .= "<br>";
                $contenuHTML .= "<a href=\"?action=add-podcasttrack\"><button id=\"ajouter\">Ajouter encore une piste</button></a>";
            }else{
                $contenuHTML .= "Vous n'avez pas donné un fichier du bon type, il faut un fichier .mp3";
            }
        }

        return $contenuHTML;
    }
}