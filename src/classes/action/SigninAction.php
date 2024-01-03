<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\db\User;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\render\AudioListRenderer;

class SigninAction extends Action
{

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if($_SERVER['REQUEST_METHOD']==='GET'){
            $contenuHTML .= <<<COUCOU
                            <form method="post" action="?action=signin">
                            <fieldset>
                                <legend> Connection </legend>
                                <input type="email" name="email" placeholder="Votre email" required>
                                <input type="password" name="mdp" placeholder="Votre mot de passe" required>
                                <button type="submit" name="signin" value="ajouter_p1">Se connecter</button>
                            </fieldset>
                            COUCOU;
        }else{ //POST
            try{
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                Auth::authenticate($email, $_POST['mdp']);

                $contenuHTML .= "Vous êtes bien connecté ! Bienvenue !";

                $utilisateur = new User($_SESSION["User"]->__get("email"), $_SESSION["User"]->__get("passwd"), $_SESSION["User"]->__get("role"));
                foreach ($utilisateur->getPlaylists() as $playlistCourante){
                    $playlist = new AudioList($playlistCourante->__get("nom"), $playlistCourante->__get("tableauPistes"));
                    $affichage = new AudioListRenderer($playlist);
                    $contenuHTML .= $affichage->render(2);
                }

            }catch(AuthException $a){
                $contenuHTML .= $a->getMessage();
                $contenuHTML .= "<a href=\"?action=signin\"><button id=\"ajouter\">Réessayer</button></a>";
            }catch(InvalidPropertyNameException $i){
                $contenuHTML .= $i;
                $contenuHTML .= "<a href=\"?action=signin\"><button id=\"ajouter\">Réessayer</button></a>";
            }
        }

        return $contenuHTML;
    }
}