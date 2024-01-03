<?php
namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{

    public function __construct(String $titre, String $cheminFichier){
        parent::__construct($titre, $cheminFichier);
    }
}