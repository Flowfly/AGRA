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
        <a href="#"><img src="img/logo.png" id="pp"></a>
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

    <form method="post" action="scripts/post-treatment.php" enctype="multipart/form-data">
        <div class="row" id="form-post">
            <div class="row" style="width:100%; padding-left: 3%; margin-bottom: 1%;">
                <div class="col-12">
                    <div class="form-group"></div>
                    <textarea class="form-control" rows="4" placeholder="Exprimez-vous" name="text-post"></textarea>
                </div>
            </div>
            <div class="row" style="width:100%; padding-left: 3%; margin-bottom: 1%;">
                <div class="col-4">
                    <input type="file" class="form-control-file" name="picture-post[]" accept="image/*" multiple>
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                    <input type="submit" class="btn btn-primary" name="submit-post" value="Publier">
                </div>
            </div>
        </div>
    </form>
    <?php
    $db = new Db('Facebook', 'localhost', 'florian', '6B8X7BzRfLUFyOrF', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $controller = new PostController($db->GetPDO());
    $count = 0;
    if (count($controller->SelectAllPosts()) == 0) {
        ?>
        <div class="post">
            <div class="row">
                <div class="col-12">
                    <h2>Aucune publication pour le moment !</h2>
                </div>
            </div>
        </div>
        <?php
    } else {
        foreach ($controller->SelectAllPosts() as $item) {
            $count++;
            ?>
            <div class="post">
                <div class="row">
                    <div class="col-10">
                        <p><span><?= $item->getText(); ?></span></p>
                    </div>
                    <div class="col-2" style="text-align: right; padding-right:3%; padding-top: 1%;">
                        <a class="fas fa-pen" id="modify" href="editPost.php?idPost=<?= $item->getId() ?>"></a>
                        <a class="fas fa-trash" id="delete" onclick="DeletePost(<?= $item->getId() ?>)"></a>
                    </div>
                </div>
                <div class="row">
                    <?php
                    for ($i = 0; $i < count($item->getImages()); $i++) {
                        ?>

                        <div class="col-3">
                            <img src="img/uploads/<?= $item->getImages()[$i]->getName() ?>" class="img-post">
                        </div>

                        <?php
                    }
                    ?>
                </div>

                <form method="post" action="scripts/post-treatment.php" id="modal-form">
                    <div class="modal fade" id="modal<?= $item->getId() ?>" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title<?= $item->getId() ?>">Suppression</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div id="delete-modal-body<?= $item->getId() ?>">
                                        <p>Voulez-vous vraiment supprimer ce post ?</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div id="delete-modal-footer<?= $item->getId() ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler
                                        </button>
                                        <input type="submit" class="btn btn-danger" data-dismiss="modal"
                                               name="post-submit-delete" value="Supprimer"
                                               onclick="SubmitModalForm(1,<?= $item->getId() ?>)">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
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
<script>
    function EditPost(param) {
        $(".modal-title" + param).text("Modification");
        $("#edit-modal-body" + param).show();
        $("#edit-modal-footer" + param).show();
        $("#delete-modal-body" + param).hide();
        $("#delete-modal-footer" + param).hide();
        $("#modal" + param).modal('show');
    }

    function DeletePost(param) {
        $(".modal-title" + param).text("Suppression");
        $("#edit-modal-body" + param).hide();
        $("#edit-modal-footer" + param).hide();
        $("#delete-modal-body" + param).show();
        $("#delete-modal-footer" + param).show();
        $("#modal" + param).modal('show');
    }

    function SubmitModalForm(param, id) {
        //If the method is called to edit the post
        if (param == 0) {
            $.ajax({
                type: "POST",
                url: "scripts/post-treatment.php",
                data: {'idpost': id, 'modal-delete-submit': true},
                success: (data) => {
                    window.location.reload();
                }
            });
        }
        //Else if the method is called to delete the post
        else if (param == 1) {
            $.ajax({
                type: "POST",
                url: "scripts/post-treatment.php",
                data: {'idpost': id, 'modal-delete-submit': true},
                success: (data) => {
                    window.location.reload();
                }
            });
        }


    }
</script>
</html>
