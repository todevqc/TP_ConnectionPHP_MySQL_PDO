<?php

    if (isset($_POST["utilisateur"], $_POST["motdepass"])
        //  ajout d'un test supplementaire
        //  s'assurer que les champs ne sont pas vide, sinon ne pas faire les operations0
        //  valeurs assimilé au vide:  0, 0.0, "0", "", NULL, FALSE, array()
        //  dans mon cas c'est acceptable
        && (!empty($_POST['utilisateur']) && !empty($_POST['motdepass']))) {

        $unsafe_utilisateur = $_POST["utilisateur"];
        $unsafe_motdepass = $_POST["motdepass"];
        
        require 'data_connexion.php';
        $pdo = creerPDO();

        //  recuperation des infots de l'utilisateur dans la BDD
        $requete_sql = "SELECT Identifiant, MotDePasse FROM tp2db.Personne WHERE Identifiant=:utilisateur";
        $pdo_statement = $pdo->prepare($requete_sql);
        $pdo_statement->execute(['utilisateur' => $unsafe_utilisateur]);

        if ($pdo_statement->rowCount() === 1) {
            $ligne = $pdo_statement->fetch();
            //  recuperer le hash contenu dans la BDD
            $hash = $ligne['MotDePasse'];
            //  verifier que le mots de passe entrer et le hash correspondent 
            if (password_verify($unsafe_motdepass, $hash)===true) {
                //  debut d'une session
                session_start();
                session_regenerate_id();
                //  creation d'une variable de session Identifiant
                $_SESSION['Identifiant'] = $ligne['Identifiant'];
    
                //  Redirection
                header('Location: index.php');
                die();
            }
        }
        //  redirection si l'utilisateur n'est pas authentifié
        //  et envoie du meme message d'erreur à afficher
        //  pas de message particulier pour un champs vide par exemple
        header('Location: index.php?erreurConnexion=erreurConnexion');
        die();
    }
