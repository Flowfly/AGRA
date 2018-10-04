<?php
session_start();
include_once("scripts/class/Post.php");
include_once("scripts/class/PostController.php");
include_once("scripts/class/Image.php");
include_once('scripts/class/Db.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="icon" href="img/favicon.png"/>
    <title>Facebook du CFPT</title>
</head>
<body>
<nav>
    <div class="row">
        <a href="index.php"><img src="img/logo.png" id="pp"></a>
    </div>
    <div class="row">
        <h3>Centre de Formation Professionnelle et Technique d'informatique</h3>
        <p>@cfpt.info</p>
    </div>
    <div class="row">
        <a href="#" style="color:black">Publications</a>
    </div>
</nav>
<section>
    <div id="cover" class="row">
        <div class="col-12" style="padding:0;">
            <img src="img/cfpt-banner.png" alt="code">
        </div>
    </div>
    <?php
    if (isset($_GET['idPost'])) {
        $id = filter_input(INPUT_GET, "idPost");
        $db = new Db('Facebook', 'localhost', 'florian', '6B8X7BzRfLUFyOrF', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $controller = new PostController($db->GetPDO());

        $posts = $controller->SelectAllPosts();
        $isValidId = false;
        for ($i = 0; $i < count($posts); $i++) {
            if ($posts[$i]->getId() == $id) {
                $isValidId = true;
                break;
            }
        }
        if ($isValidId) {
            $postToEdit = $controller->SelectOnePost($id);
            ?>
            <form method="post" action="scripts/post-treatment.php" enctype="multipart/form-data" id="modal-form">
                <div class="post">

                    <br>
                    <div class="row">
                        <div class="col-11" style="margin:auto;">
                            <textarea class="form-control" rows="4"
                                      name="text-post"><?= $postToEdit->getText() ?></textarea>
                        </div>
                    </div>
                    <div class="row" style="padding-left: 5.5%; padding-right: 5.5%; padding-top: 1%;">
                        <?php
                        for ($i = 0; $i < count($postToEdit->getImages()); $i++) {
                            ?>
                            <form method="post" action="scripts/post-treatment.php">
                            <div class="col-2">
                                <div class="container">
                                    <img src="img/uploads/<?= $postToEdit->getImages()[$i]->getName(); ?>"
                                         class="img-edit-modal" onclick="OpenModal(<?=$i?>)">
                                    <div class="trash-icon-container">
                                        <i class="fas fa-trash" style="color:#f24646;"></i>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="modal fade" id="modal<?=$i?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Suppression</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">

                                            <div id="delete-modal-body">
                                                <p>Voulez-vous vraiment supprimer ce post ?</p>
                                                <h4>Cette action est irréversible</h4>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div id="delete-modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                                <input type="submit" class="btn btn-danger" data-dismiss="modal"
                                                       name="post-submit-delete" value="Supprimer" onclick="DeleteImage('<?= $postToEdit->getImages()[$i]->getName(); ?>')">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <br>
                    <div class="row" style="padding-left: 5.5%; padding-right: 5.5%; padding-top: 1%;">
                        <input type="file" class="form-control-file" name="picture-post[]" accept="image/*" multiple>
                    </div>
                    <div class="row" style="padding-left: 5.5%; padding-right: 5.5%; padding-top: 1%;">
                        <div class="col-12">
                            <input type="submit" value="Modifier" class="btn btn-primary">
                        </div>
                    </div>
                    <br>
                </div>
            </form>


            <?php
        } else {
            ?>
            <div class="post">
                <div class="row" style="text-align: center;">
                    <div class="col-12">
                        <br>
                        <hr>
                        <h1>Oups !</h1>
                        <h1>Nous ne trouvons malheureusement pas votre publication...</h1>
                        <h1>Réessayez plus tard !</h1>
                        <hr>
                        <br>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="post">
            <div class="row" style="text-align: center;">
                <div class="col-12">
                    <br>
                    <hr>
                    <h1>Oups !</h1>
                    <h1>Nous ne trouvons malheureusement pas votre publication...</h1>
                    <h1>Réessayez plus tard !</h1>
                    <hr>
                    <br>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

</section>
</body>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
<script type="text/javascript">
    function OpenModal(param)
    {
        $("#modal" + param).modal("show");
    }

    function DeleteImage(param)
    {
        $.ajax({
            type: "POST",
            url: "scripts/post-treatment.php",
            data: {'imageName': param},
            success: (data) => {
                window.location.reload();
            }
        });
    }
</script>

</html>
