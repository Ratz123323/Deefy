<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException;
use PDO;

class Playlist extends AudioList
{
    public function ajouterPiste(AudioTrack $piste): void{
        $this->tableauPistes[] = $piste;
        $this->dureeTotale += $piste->__get("duree");
        $this->nbPistes++;
    }

    public function supprimerPiste(int $indice):void{
        $this->dureeTotale -= $this->tableauPistes[$indice]->__get("duree");
        unset($this->tableauPistes[$indice]);
        $this->nbPistes--;
    }

    public function ajouterListePiste(array $listeAAjouter){
        foreach($listeAAjouter as $p){
            if(!in_array($p, $this->tableauPistes)){
                self::ajouterPiste($p);
            }
        }
    }

    public function getTrackList():array{
        $tableauDobjetsAudioTrack = array();

        //requete qui permet de récupérer toutes les id des pistes audios de la playlist
        $requeteA = <<<REQUETEA
                        select id_track from playlist
                        inner join playlist2track on playlist.id = playlist2track.id_pl
                        where nom = ?
                    REQUETEA;

        //requete qui permet de récupérer le détail de chaque piste audio de la playlist
        $requeteB = <<<REQUETEB
                        select * from track
                        where id = ?
                    REQUETEB;

        // On prépare la requête A
        $stA = ConnectionFactory::$db->prepare($requeteA);

        // On complète la requete A avec le nom de la playlist
        $stA->bindParam(1, $this->nom);

        // 0n exécute la requête A
        $stA->execute();

        //on parcourt chaque id des pistes audios de la playlist
        while ($rowA = $stA->fetch(PDO::FETCH_ASSOC)){
            // On prépare la requête B
            $stB = ConnectionFactory::$db->prepare($requeteB);

            // On complète la requete B avec le nom de la piste audio
            $stB->bindParam(1, $rowA["id_track"]);

            // 0n exécute la requête B
            $stB->execute();

            // on récupère pour chaque piste audio de la playlist toutes les informations la concernant
            while ($rowB = $stB->fetch(PDO::FETCH_ASSOC)){
                if($rowB["type"] === "A"){ // type A = AlbumTrack
                    //on recrée la track courante de type AlbumTrack
                    $trackCourante = new AlbumTrack($rowB["titre"], $rowB["filename"]);
                    $trackCourante->__set("genre", $rowB["genre"]);
                    $trackCourante->__set("duree", $rowB["duree"]);
                    $trackCourante->__set("artiste", $rowB["artiste_album"]);
                    $trackCourante->__set("annee", $rowB["annee_album"]);
                    $trackCourante->__set("album", $rowB["titre_album"]);
                    $trackCourante->__set("numPiste", $rowB["no_piste_dans_liste"]);

                    //on ajoute la nouvelle track à la playlist
                    $tableauDobjetsAudioTrack[] = $trackCourante;

                }else if($rowB["type"] === "P"){ // type P = PodcastTrack
                    //on recrée le podcast courant de type PodcastTrack
                    $podcastCourant = new PodcastTrack($rowB["titre"], $rowB["filename"]);
                    $podcastCourant->__set("genre", $rowB["genre"]);
                    $podcastCourant->__set("duree", $rowB["duree"]);
                    $podcastCourant->__set("auteur", $rowB["auteur_podcast"]);
                    $podcastCourant->__set("date", $rowB["date_posdcast"]);
                    $podcastCourant->__set("numPiste", $rowB["no_piste_dans_liste"]);

                    //on ajoute le nouveau podcast à la playlist
                    $tableauDobjetsAudioTrack[] = $podcastCourant;
                }
            }
        }

        return $tableauDobjetsAudioTrack;
    }

    public static function find(int $idPlaylist): Playlist
    {
        //connection à la base de données
        ConnectionFactory::setConfig(__DIR__ . '../../../config/db.config.ini');
        ConnectionFactory::makeConnection();

        $tableauDobjetsAudioTrack = array();

        //requete qui permet de récupérer toutes les id des pistes audios de la playlist
        $requeteA = <<<REQUETEA
                        select playlist2track.id_track, playlist.nom from playlist
                        inner join playlist2track on playlist.id = playlist2track.id_pl
                        where playlist.id = ?
                    REQUETEA;

        //requete qui permet de récupérer le détail de chaque piste audio de la playlist
        $requeteB = <<<REQUETEB
                        select * from track
                        where id = ?
                    REQUETEB;

        // On prépare la requête A
        $stA = ConnectionFactory::$db->prepare($requeteA);

        // On complète la requete A avec le nom de la playlist
        $stA->bindParam(1, $idPlaylist);

        // 0n exécute la requête A
        $stA->execute();

        //on parcourt chaque id des pistes audios de la playlist
        while ($rowA = $stA->fetch(PDO::FETCH_ASSOC)){

            //indice de la piste dans la playlist
            $i = 1;
            // on créé le nouvel objet à retourner
            $playlist = new Playlist($rowA["nom"]);

            // On prépare la requête B
            $stB = ConnectionFactory::$db->prepare($requeteB);

            // On complète la requete B avec le nom de la piste audio
            $stB->bindParam(1, $rowA["id_track"]);

            // 0n exécute la requête B
            $stB->execute();

            // on récupère pour chaque piste audio de la playlist toutes les informations la concernant
            while ($rowB = $stB->fetch(PDO::FETCH_ASSOC)){
                if($rowB["type"] === "A"){ // type A = AlbumTrack
                    //on recrée la track courante de type AlbumTrack
                    $trackCourante = new AlbumTrack($rowB["titre"], $rowB["filename"]);
                    $trackCourante->__set("genre", $rowB["genre"]);
                    $trackCourante->__set("duree", $rowB["duree"]);
                    $trackCourante->__set("artiste", $rowB["artiste_album"]);
                    $trackCourante->__set("annee", $rowB["annee_album"]);
                    $trackCourante->__set("album", $rowB["titre_album"]);
                    $trackCourante->__set("numPiste", $i);
                    $i++;

                    //on ajoute la nouvelle track à la playlist
                    $tableauDobjetsAudioTrack[] = $trackCourante;

                }else if($rowB["type"] === "P"){ // type P = PodcastTrack
                    //on recrée le podcast courant de type PodcastTrack
                    $podcastCourant = new PodcastTrack($rowB["titre"], $rowB["filename"]);
                    $podcastCourant->__set("genre", $rowB["genre"]);
                    $podcastCourant->__set("duree", $rowB["duree"]);
                    $podcastCourant->__set("auteur", $rowB["auteur_podcast"]);
                    $podcastCourant->__set("date", $rowB["date_posdcast"]);
                    $podcastCourant->__set("numPiste", $i);

                    //on ajoute le nouveau podcast à la playlist
                    $tableauDobjetsAudioTrack[] = $podcastCourant;
                    $i++;
                }
            }
        }

        $playlist->ajouterListePiste($tableauDobjetsAudioTrack);

        return $playlist;
    }
}