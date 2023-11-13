<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>

    <link rel="stylesheet" href="../Public/style/profil.css" />
</head>

<body>


    <div class="container">
        <div class="titre">Bienvenue sur ta page profil, !</div>
        <div class="center">
            <a href="/logout"><button class="btn-logout" type="submit">Se déconnecter</button></a>
        </div>

        <div class="container-stats">
            <div class="profile-pic">
                <img src="./" alt="" style="max-height: 250px" />
                <div class="pic-box">
                    <div class="pic-title-box">Changer de photo de profil</div>
                    <form action="/profile" method="POST" enctype="multipart/form-data">
                        <input class="test" type="file" name="profile_picture" required />
                        <input type="submit" value="Ajouter la photo sélectionnée" />
                    </form>
                </div>
            </div>
        </div>

        <div class="container-stats">
            <div class="grey-box">
                <div class="grey-title">Changer de nom d'utilisateur</div>
                <form class="formulaire" action="/update-username" method="POST">
                    <input class="input" type="text" name="oldusername" placeholder="Ancien nom d'utilisateur..." required />
                    <input class="input" type="text" name="newusername" placeholder="Nouveau nom d'utilisateur..." required />
                    <input class="input" type="text" name="newusernameconfirm" placeholder="Confirmer nouveau nom..." required />
                    <div>
                        <button class="btn" type="submit">Confirmer</button>
                    </div>
                </form>
            </div>

            <div class="grey-box">
                <div class="grey-title">Changer de mot de passe</div>
                <form class="formulaire" action="/update-password" method="POST">
                    <input class="input" type="password" name="oldpassword" placeholder="Ancien mot de passe..." required />
                    <input class="input" type="password" name="newpassword" placeholder="Nouveau mot de passe..." required />
                    <input class="input" type="password" name="newpwdconfirm" placeholder="Confirmer nouveau mot de passe..." required />

                    <div class="">
                        <button class="btn" type="submit">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>



        <div class="center">
            <form action="/delete-account" onSubmit="if(!confirm('Es-tu sûr.e de vouloir nous quitter ?')){return false;}">
                <button type="submit" class="btn">Supprimer mon compte</button>
            </form>
        </div>
    </div>
</body>

</html>