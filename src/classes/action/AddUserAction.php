<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\AuthException;

class AddUserAction extends Action {

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if($_SERVER['REQUEST_METHOD']==='GET'){
            /* ANCIENNE FORMULE
            $contenuHTML .= <<<COUCOU
                            <form method="post" action="?action=add-user">
                                <fieldset>
                                    <legend> Nouvel utilisateur </legend>
                                    <input type="email" name="email" placeholder=Email required>
                                    <input type="age" name="age" placeholder=Âge required>
                                    <!--<input type=\"genre\" placeholder=\"Genre Musical Préféré\">-->
                                    <select type="genre" name="genre" name="select">
                                        <option value="Classique">Classique</option>
                                        <option value="Electro">Electro</option>
                                        <option value="Jazz">Jazz</option>
                                    </select>
                                    <button type="submit" name="valider_inscription" value="valid_f1">Connexion</button>
                                </fieldset>
                            </form>
                        COUCOU;
            */

            // NOUVELLE FORMULE
            $contenuHTML .= <<<COUCOU
                            <form method="post" action="?action=add-user">
                                <fieldset>
                                    <legend> Nouvel utilisateur </legend>
                                    <input type="email" name="email" placeholder=Email required>
                                    <input type="password" name="mdp" placeholder="Votre mot de passe" required>
                                    <input type="password" name="mdpBis" placeholder="Réécriver votre mot de passse" required>
                                    <!--<input type=\"genre\" placeholder=\"Genre Musical Préféré\">-->
                                    <button type="submit" name="valider_inscription" value="valid_f1">S'inscrire</button>
                                </fieldset>
                            </form>
                        COUCOU;

        }else{ // POST
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            if($_POST['mdp'] === $_POST['mdpBis']){
                $mdp = $_POST['mdp'];
                try{
                    Auth::register($email, $mdp);
                    $contenuHTML .= <<<COUCOU
                                <p><b>Votre enregistrement a bien réussi !</b></p>
                                <p>Email: <b>$email</b>,</p>
                            COUCOU;
                    $contenuHTML .= "<a href=\"?action=add-playlist\"><button id=\"ajouter\">Ajouter une playlist</button></a>";
                }catch(AuthException $a){
                    $contenuHTML .= $a->getMessage();
                    $contenuHTML .= "<a href=\"?action=add-user\"><button id=\"ajouter\">Réessayer</button></a>";
                }
            }else{
                $contenuHTML .= "Les 2 mots de passe ne sont pas identiques, veuillez réessayer";
                $contenuHTML .= "<a href=\"?action=add-user\"><button id=\"ajouter\">Réessayer</button></a>";
            }
        }

        return $contenuHTML;
    }

}