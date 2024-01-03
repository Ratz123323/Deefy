<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException;

class AudioTrack
{
    private String $titre, $genre, $nomFichier, $auteur, $date, $artiste, $album;
    private int $duree, $annee, $numPiste;

    public function __construct(String $ti, String $no){
        $this->titre = $ti;
        $this->nomFichier = $no;
        $this->genre = "inconnnu";
        $this->duree = 1;
        $this->auteur = "inconnu";
        $this->date = "3000";
        $this->artiste = "inconnu";
        $this->album = "inconnu";
        $this->annee = 1000;
        $this->numPiste = 0;
    }

    public function __toString(): String {
        return json_encode($this);
    }

    public function __get(String $attribut)
    {
        if(property_exists($this, $attribut)) {
            return $this->$attribut;
        }else{
            throw new InvalidPropertyNameException(get_called_class() . " invalid property : \"$attribut\"");
        }
    }

    public function __set(String $name, mixed $value): void{
        if(property_exists($this, $name)){
            if($name == "duree" && $value < 0){
                throw new InvalidPropertyValueException('valeur invalide car inférieure à zéro');
            }else if($name == "titre" || $name == "nomFichier"){
                throw new NonEditablePropertyException('attribut non modifiable');
            }
            $this->$name = $value;
        }else{
            throw new InvalidPropertyNameException("invalid property : $name");
        }
    }

    public function insertTrack(AudioTrack $pisteAAjouter){
        //si la piste à ajouter est un AlbumTrack
        if($pisteAAjouter instanceof AlbumTrack){
            $requeteAjoutA = <<<REQUETE
                                INSERT INTO `track` (`titre`, `genre`, `duree`, `filename`, `type`, `artiste_album`, `titre_album`, `annee_album`, `numero_album`, `auteur_podcast`, `date_posdcast`)
                                VALUE ($this->titre, $this->genre, $this->duree, $this->nomFichier, 'A', $this->artiste, $this->album, $this->annee, 1, NULL, NULL)
                            REQUETE;
            //connection à la base de données
            $stA = ConnectionFactory::$db->prepare($requeteAjoutA);

            // 0n exécute la requête
            $stA->execute();

        }else if($pisteAAjouter instanceof PodcastTrack){ //si la piste à ajouter est un Podcasttrack
            $requeteAjoutP = <<<REQUETE
                                INSERT INTO `track` (`titre`, `genre`, `duree`, `filename`, `type`, `artiste_album`, `titre_album`, `annee_album`, `numero_album`, `auteur_podcast`, `date_posdcast`)
                                VALUE ($this->titre, $this->genre, $this->duree, $this->nomFichier, 'P', NULL, NULL, NULL, NULL, $this->auteur, $this->date)
                            REQUETE;

            //connection à la base de données
            $stA = ConnectionFactory::$db->prepare($requeteAjoutP);

            // 0n exécute la requête
            $stA->execute();
        }
    }

}