<?php
namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack
{

    public function __construct(String $titre, String $cheminFichier){
        parent::__construct($titre, $cheminFichier);
    }
}