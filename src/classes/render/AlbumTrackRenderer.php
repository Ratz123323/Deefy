<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer
{

    public function __construct(AlbumTrack $albmtrack){
        parent::__construct($albmtrack);
    }

    public function short():string{
        return "<titre><h4>".$this->audioTrack->titre."</h4></titre>"."<p><audio controls src=\"".$this->audioTrack->nomFichier."\"></audio></p>";
    }

    public function long():string{
        return "<titre><h4>"."========= ".$this->audioTrack->titre." ========="."</h4></titre>"."<p><audio controls src=\"".$this->audioTrack->nomFichier."\"></audio></p>";
    }

}