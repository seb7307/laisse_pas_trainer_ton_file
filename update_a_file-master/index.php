<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Traitement upload</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="formulaire.css";>
</head>

<body>

<?php
/**
 * Created by PhpStorm.
 * User: etienne
 * Date: 09/10/18
 * Time: 07:24
 */

$message = "";
if(isset($_POST['submit'])) {
    if (count($_FILES['fichier']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['fichier']['name']); $i++) {
            if (!empty($_FILES['fichier']['tmp_name'][$i])) {
                if (is_uploaded_file($_FILES['fichier']['tmp_name'][$i])) {
                    $typeMime = mime_content_type($_FILES['fichier']['tmp_name'][$i]);
                    if ($typeMime == 'image/png' or $typeMime == 'image/jpeg' or $typeMime == 'image/gif') {
                        $size = filesize($_FILES['fichier']['tmp_name'][$i]);
                        if ($size > 1000000) {
                            $message = "Le fichier ne doit pas dépasser la taille de 1Mo !";
                        } else {
                            $cheminEtNomTemporaire = $_FILES['fichier']['tmp_name'][$i];

                            // Récupérer l'extension du fichier uploadé

                            $extension = substr(strrchr(($_FILES['fichier']['name'][$i]), "."), 1);

                            // Je donne un nouveau nom au fichier : imageUniqueID.extension

                            $nouveauNom = uniqid('image') . '.' . $extension;

                            // Le fichier sera sauvegardé avec le nouveau nom

                            $cheminEtNomDefinitif = 'Upload/' . $nouveauNom;

                            // Fonction qui permet de stocker le fichier du dossier Temp au dossier final

                            $moveIsOk = move_uploaded_file($cheminEtNomTemporaire, $cheminEtNomDefinitif);
                        }
                        if ($moveIsOk) {
                            $message = "Le fichier a été correctement uploadé !";
                        } else {
                            $message = "L'upload a échoué !";
                        }
                    } else {
                        $message = "On ne peut uploader qu'un fichier image (jpeg, gif, png)";
                    }
                } else {
                    $message = "Un problème a eu lieu lors de l'upload !";
                }
            } else {
                $message = "Ce fichier n'est pas validé par Raiponce !";
            }
        }
    }
}
?>
<h1>Etat du transfert :</h1>

<p><?php echo $message; ?></p>
<?php echo '<hr>' ?>

<form action="" method="post" enctype="multipart/form-data">
    <div>
        <label for='fichier'>Ajouter un fichier :</label>
        <p><input type="file" name="fichier[]" id="fichier" multiple="multiple"></p>
    </div>

    <p><input type="submit" name="submit" value="Enregistrer" </p>
</form>

<hr>
<div class="row">
<?php
$it = new FilesystemIterator(dirname("Upload/Upload"));
foreach ($it as $fileinfo) {?>

<div class="col-4">

    <div class="card" style="width: 18rem;">

            <img class="card-img-top" src="<?php echo "Upload/". $fileinfo->getFilename(); ?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?php echo $fileinfo->getFilename(); ?></h5>
            </div>
            <form method="post" action="">
            <button type="submit" class="btn btn-danger" name="supr" value="supprimer">Supprimer</button>
            </form>
    </div>
    </div>

<?php }

if (@$_POST['supr'] == 'supprimer'){
    unlink("Upload/".$fileinfo->getFilename());
    echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
}
?>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>