<?php
session_start();
session_regenerate_id();
//  verifier si l'utilisateur est authentifié
if (isset($_SESSION['Identifiant'])) {
    //  verifier si les donnée sont envoyer
    if (isset($_POST['contenu_article'], $_POST['idArticle']) 
        //  ajout d'un test supplementaire
        //  s'assurer que le champs ID n'est pas vide, sinon ne pas faire les operations
        && !empty($_POST['idArticle'])){
        
        $unsafe_contenu = $_POST['contenu_article'];
        $unsafe_idArticle = (int)$_POST['idArticle'];

        require 'data_connexion.php';
        $pdo = creerPDO();

        //  mise a jour du contenu de l'article avec nettoyage SQL
        $requete_sql = 'UPDATE tp2db.Article SET Contenu=:le_contenu WHERE Id=:id_article';
        $pdo_statement = $pdo->prepare($requete_sql);
        $resultat = $pdo_statement->execute(['le_contenu' => $unsafe_contenu, 'id_article' => $unsafe_idArticle]);

        //  redirection
        header('Location: index.php');
        die();
    }
}
//  redirection si l'utilisateur n'est pas authentifié
//  et envoie du meme message d'erreur à afficher
header('Location: index.php?erreurConnexion=erreurConnexion');
die();