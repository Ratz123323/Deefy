<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioListRenderer implements Renderer
{
    protected AudioList $audioListR;

    public function __construct(AudioList $audioL){
        $this->audioListR = $audioL;
    }

    public function render(int $selector):string{
        $res = "<titre><h1>" . $this->audioListR->nom . "</h1></titre><br>";

        foreach ($this->audioListR->tableauPistes as $key) {
            if($key instanceof AlbumTrack){
                $renderer = new AlbumTrackRenderer($key);
            }else if ($key instanceof PodcastTrack) {
                $renderer = new PodcastTrackRenderer($key);
            }else{
                throw new InvalidPropertyNameException(get_called_class() . " invalid property : \"$key\"");
            }
            $res .= $renderer->short();
        }

        $res .= "<p>Nombre de pistes : " . $this->audioListR->__get("nbPistes") . "</p>" . "<p>Durée totale : " . $this->audioListR->dureeTotale . " s</p><br>";
        return $res;
    }

}