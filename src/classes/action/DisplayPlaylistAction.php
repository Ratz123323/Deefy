<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action
{

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        try{
            if(Auth::verifierAppartenance($_GET['id'])){
                $playlistAAficher = Playlist::find($_GET['id']);
                $affichage = new AudioListRenderer($playlistAAficher);
                $contenuHTML .= $affichage->render(2);
            }else{
                $contenuHTML .= "Vous n'avez pas les droits";
            }

        }catch(InvalidPropertyNameException $e1){
            $contenuHTML .= print $e1;
        }

        return $contenuHTML;
    }
}