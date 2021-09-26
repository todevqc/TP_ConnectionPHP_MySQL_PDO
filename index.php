<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>
<body>
    <section class="content">
        
        <?php
            // debut de la session
            session_start();
            session_regenerate_id();

            // connexion à la base de données
            require 'data_connexion.php';
            $pdo = creerPDO();

            //////////////////////////////////////////////////////
            // verifier si l'utilisateur est déjà authentifié
            if (isset($_SESSION['Identifiant'])) :
                
                //  recuperation du premier article et affichage
                $requete_sql = "SELECT * FROM tp2db.Article WHERE Id = 1";
                $pdo_statement = $pdo->prepare($requete_sql);
                $pdo_statement->execute([]);
            
                foreach($pdo_statement as $ligne) :
                ?>
                    <article>
                        <header><?php $ligne["Entete"]?></header>
                        <form class="modification-form" method="post" action="traitement_mod_article.php">
                            <?php
                                //  a cause d'un petit defaut d'affichage et vu l'obligation de nettoyage du text
                                //  a l'affichage j'utilise  htmlspecialchars dans un echo.
                                echo '<textarea name="contenu_article">'.htmlspecialchars($ligne["Contenu"]).'</textarea>';
                            ?>
                            <input type="hidden" name="idArticle" value="<?php echo$ligne["Id"];?>">
                            <input type="submit" value="Soumettre les modifications">
                        </form>
                        <footer><?php echo $ligne["Pied"];?></footer>
                    </article>
                <?php 
                endforeach;
                ?>

                <!--    Affichage du formulaire de deconnexion avec le nom de l'utilisateur connecté-->
                <form class="connect-form" method="get" action="deconnexion.php">            
                    <label for="utilisateur">Bonjour <?php echo $_SESSION['Identifiant'];?></label>
                    <input type="submit" value="Déconnexion">
                </form>
            
            <?php
            //////////////////////////////////////////////////////////
            else:   //  si l'utilisateur n'est pas encore authentifié

                //  recuperation du premier article et affichage
                $requete_sql = "SELECT * FROM tp2db.Article WHERE Id = 1";
                $pdo_statement = $pdo->prepare($requete_sql);
                $pdo_statement->execute([]);

                foreach($pdo_statement as $ligne) :
                    echo '<article>';
                    echo '<header>'. $ligne["Entete"].'</header>';
                    echo '<p>'.htmlspecialchars($ligne["Contenu"]).'</p>';
                    echo '<footer>'. $ligne["Pied"].'</footer>';
                    echo '</article>';
                endforeach;
                ?>

                <!--    formulaire de connexion-->
                
                <form class="connect-form" method="POST" action="authentification.php"> 

                        <?php   //  affichage en cas d'erreur de connexion
                            if(isset($_GET['erreurConnexion']) && ($_GET['erreurConnexion']==='erreurConnexion')) :
                                    echo '<p class="erreurConnexion">Erreur de connexion !!!!</p>';
                            endif;
                        ?>
                        
                <!--    suite du formulaire de connexion-->                                               
                    <label for="utilisateur">Identifiant</label>
                    <input type="text" id="utilisateur" name="utilisateur">
                    <label for="motdepass">Mot de passe</label>
                    <input type="password" id="motdepass" name="motdepass">
                    <input type="submit" value="Connexion">
                </form>
            
            
            <?php   
            ///////////////////////////////////////////////////
            endif;     // fin du test d'authentification
            ?>

    </section>
</body>
</html>