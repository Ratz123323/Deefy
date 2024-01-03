<?php

namespace iutnc\deefy\db;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use PDO;
class User
{
    // Email et mdp de l'utilisateur
    private string $email, $passwd;

    // role de l'utilisateur
    private int $role;

    public function __construct(string $e, string $mdp, int $r){
        $this->email = $e;
        $this->passwd = $mdp;
        $this->role = $r;
    }

    public function __get(string $nom){
        if(property_exists($this, $nom)){
            return $this->$nom;
        }else{
            throw new InvalidPropertyNameException(get_called_class() . " invalid property : \"$nom\"");
        }
    }

    public function getPlaylists(): array{

        $tableauDobjetsPlaylist = array();

        //requete qui permet d'obtenir l'id et le nom des playlists d'un utilisateur
        $requeteA = <<<REQUETEA
                        select nom, id_pl from user
                        inner join user2playlist on user.id = user2playlist.id_user
                        inner join playlist on user2playlist.id_pl = playlist.id
                        where user.email = ?
                    REQUETEA;

        //requete qui permet de récupérer tout le contenu d'une playlist
        $requeteB = <<<REQUETEB
                            select * from track
                            inner join playlist2track on track.id = playlist2track.id_track
                            where id_pl = ?
                        REQUETEB;

        // On prépare la requête A
        $stA = ConnectionFactory::$db->prepare($requeteA);

        // On complète la requete A avec l'email de l'utilisateur
        $stA->bindParam(1, $this->email);

        // 0n exécute la requête A
        $stA->execute();

        //on parcourt toutes les playlist de l'utilisateur
        while ($row = $stA->fetch(PDO::FETCH_ASSOC)){
            //création de la playlist
            $playlistCourante = new Playlist($row['nom']);

            // On prépare la requête B
            $stB = ConnectionFactory::$db->prepare($requeteB);

            // On complète la requete B avec l'email de l'utilisateur
            $stB->bindParam(1, $row['id_pl']);

            // 0n exécute la requête B
            $stB->execute();

            // on parcourt toutes les musiques de la playlist
            while($audio = $stB->fetch(PDO::FETCH_ASSOC)){
                if($audio["type"] === "A"){ // type A = AlbumTrack
                    //on recrée la track courante de type AlbumTrack
                    $trackCourante = new AlbumTrack($audio["titre"], $audio["filename"]);
                    $trackCourante->__set("genre", $audio["genre"]);
                    $trackCourante->__set("duree", $audio["duree"]);
                    $trackCourante->__set("artiste", $audio["artiste_album"]);
                    $trackCourante->__set("annee", $audio["annee_album"]);
                    $trackCourante->__set("album", $audio["titre_album"]);
                    $trackCourante->__set("numPiste", $audio["no_piste_dans_liste"]);

                    //on ajoute la nouvelle track à la playlist
                    $playlistCourante->ajouterPiste($trackCourante);

                }else if($audio["type"] === "P"){ // type P = PodcastTrack
                    //on recrée le podcast courant de type PodcastTrack
                    $podcastCourant = new PodcastTrack($audio["titre"], $audio["filename"]);
                    $podcastCourant->__set("genre", $audio["genre"]);
                    $podcastCourant->__set("duree", $audio["duree"]);
                    $podcastCourant->__set("auteur", $audio["auteur_podcast"]);
                    $podcastCourant->__set("date", $audio["date_posdcast"]);
                    $podcastCourant->__set("numPiste", $audio["no_piste_dans_liste"]);

                    //on ajoute le nouveau podcast à la playlist
                    $playlistCourante->ajouterPiste($podcastCourant);
                }
            }
            // on ajoute la playliste courante (remplie) dans notre objet qui va contenir toutes les playlists d'un utilisateur
            $tableauDobjetsPlaylist[] = $playlistCourante;
        }
        return $tableauDobjetsPlaylist;
    }
}