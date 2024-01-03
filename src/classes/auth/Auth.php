<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\db\User;
use iutnc\deefy\exception\AuthException;
use PDO;

class Auth
{

    public static function authenticate(string $email, string $mdp){

        //connection à la base de données
        ConnectionFactory::setConfig(__DIR__ . '../../config/db.config.ini');
        ConnectionFactory::makeConnection();

        // requete sql : "select * from user where email = $email;"
        $requete = "select * from user where email=?";

        $st = ConnectionFactory::$db->prepare($requete);

        // On met l'email de l'utilisateur en condition de la requête
        $st->bindParam(1, $email);

        // Puis on exécute la requête
        $st->execute();

        // vérification de la présence de l'utilisateur dans la base
        if($row = $st->fetch(PDO::FETCH_ASSOC)){

            // si le mot de passe n'est pas bon, on lève une exception
            if(password_verify($mdp, $row['passwd'])){
                // on stock l'utilisateur en session
                $user = new User($row["email"], $row["passwd"], $row["role"]);
                $_SESSION["User"] = $user;
            }else{
                throw new AuthException(get_called_class() . " invalid password");
            }

        }else{ // si l'utilisateur n'existe pas, on lève une exception
            throw new AuthException(get_called_class() . " invalid user");
        }
    }
    public static function register(string $email, string $mdp){

        //varification de la taille du mot de passe
        if(strlen($mdp)>10){

            //connection à la base de données
            ConnectionFactory::setConfig(__DIR__ . '../../config/db.config.ini');
            ConnectionFactory::makeConnection();

            // requete sql : "select email from user where email = $email;"
            $prerequete = "select email from user where email=?";

            $prest = ConnectionFactory::$db->prepare($prerequete);

            // On met l'email de l'utilisateur en condition de la requête
            $prest->bindParam(1, $email);

            // Puis on exécute la requête
            $prest->execute();

            // on récupère normalement aucune ligne
            $row = $prest->fetch(PDO::FETCH_ASSOC);

            // vérification de la NON présence de l'utilisateur dans la base
            if($row == null){

                // encodage du mot de passe
                $hash = password_hash($mdp, PASSWORD_DEFAULT, ['cost'=>12]);

                //requete sql pour ajouter le nouvel utilisateur à la base
                $requete = "insert into user (email, passwd) value(?, ?)";

                $st = ConnectionFactory::$db->prepare($requete);

                // On met l'email et le mot de passe de l'utilisateur en condition de la requête
                $st->bindParam(1, $email);
                $st->bindParam(2, $hash);

                // Puis on exécute la requête
                $st->execute();
            }else{
                throw new AuthException(get_called_class() . " Vous avez déjà un compte ? ");
            }
        }else{ // si la taille du mot de passe est trop petite
            throw new AuthException(get_called_class() . " mot de passe trop petit ");
        }
    }

    // Méthode qui permet de vérifier si l'id de playlist donné correspond à une playlist de l'utilisateur
    public static function verifierAppartenance(int $id){

        // on vérifie si l'utilisateur est administrateur (role = 100)
        if($_SESSION["User"]->__get("role") == 100){
            return true;
        }
        // sinon on vérifie si l'id de playlist donné correspond à une playlist de l'utilisateur
        $trouve = false;

        //connection à la base de données
        ConnectionFactory::setConfig(__DIR__ . '../../config/db.config.ini');
        ConnectionFactory::makeConnection();

        // requete sql qui permet de récupérer les playlists d'un utilisateur
        $requete = <<<FIN
                        select id_pl from user2playlist
                        inner join user on user.id = user2playlist.id_user
                        where user.email = ?
                    FIN;

        $st = ConnectionFactory::$db->prepare($requete);

        // on complète la requete avec l'email de l'utilisateur
        $userEmail = $_SESSION["User"]->__get("email");
        $st->bindParam(1, $userEmail);

        // puis on exécute la requête
        $st->execute();

        // on vérifie si l'utilisateur possède la playlist donnée
        while($row = $st->fetch(PDO::FETCH_ASSOC)){
            if($row["id_pl"] == $id){
                $trouve = true;
            }
        }
        return $trouve;
    }
}