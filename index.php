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
    <title>Facebook du CFPT</title>
</head>
<body>
<nav>
    <div class="row">
        <img src="img/logo.png" id="pp">
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
            <img src="img/code.png" alt="code">
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
    $db = new Db('Facebook', 'localhost', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $controller = new PostController($db->GetPDO());
    $count = 0;
    foreach ($controller->SelectAllPosts() as $item) {
        $count++;
        ?>
        <div class="post">
            <div class="row">
                <div class="col-10">
                    <p><span><?= $item->getText(); ?></span></p>
                </div>
                <div class="col-2" style="text-align: right; padding-right:3%; padding-top: 1%;">
                    <a class="fas fa-pen" id="modify" onclick="EditPost(<?= $item->getId() ?>)"></a>
                    <a class="fas fa-trash" id="delete" onclick="DeletePost(<?= $item->getId() ?>)"></a>
                </div>
            </div>
            <?php
            for ($i = 0; $i < count($item->getImages()); $i++) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <img src="img/uploads/<?= $item->getImages()[$i]->getName() ?>" class="img-post">
                    </div>
                </div>
                <?php
            }
            ?>

            <form method="post" action="scripts/post-treatment.php" id="modal-form">
                <div class="modal fade" id="modal<?= $item->getId() ?>" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title<?= $item->getId() ?>">Modification</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="idpost-delete" id="idpost-delete"
                                       value="<?= $item->getId() ?>">
                                <div id="edit-modal-body<?= $item->getId() ?>">
                                    <textarea class="form-control" rows="4"
                                              name="text-post-update"><?= $item->getText(); ?></textarea>
                                    <input type="file" class="form-control-file" name="picture-post-update[]"
                                           accept="image/*" multiple>
                                </div>
                                <div id="delete-modal-body<?= $item->getId() ?>">
                                    <p>Voulez-vous vraiment supprimer ce post ?</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div id="edit-modal-footer<?= $item->getId() ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary" data-dismiss="modal"
                                            name="post-submit-edit">Sauvegarder
                                    </button>
                                </div>
                                <div id="delete-modal-footer<?= $item->getId() ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                    <input type="submit" class="btn btn-danger" data-dismiss="modal"
                                           name="post-submit-delete" value="Supprimer" onclick="SubmitModalForm()">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    ?>
    <div class="post">
        <div class="row">
            <div class="col-10">
                <p>
                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis ipsum, mollitia. Ad, aperiam doloremque earum et excepturi facilis, ipsa minima nesciunt nobis pariatur porro quo rerum sint ullam ut voluptate!</span>
                    <span>Adipisci aperiam at autem consectetur consequuntur culpa cumque dolore dolorum eum ex explicabo fugit harum iusto modi molestiae natus neque nihil possimus praesentium, provident quibusdam quo soluta suscipit tenetur ullam.</span>
                    <span>Accusantium commodi consequuntur dolore, doloremque expedita id illum laborum nam natus, nihil nisi porro quo ratione sint sunt! Ad beatae debitis doloribus esse itaque minus perferendis quam tenetur vel veniam?</span>
                    <span>Ducimus eius, ex incidunt nesciunt odit quam repellendus ullam. Blanditiis eius error, ipsam iusto nisi, non obcaecati optio qui ratione sapiente sint voluptas! Aliquam, nulla, odit? Dignissimos eveniet expedita minus!</span>
                    <span>Beatae dolores ea in laborum quia. Cum cupiditate eum non porro reiciendis ullam voluptatibus. Accusantium consectetur explicabo odit totam. A asperiores commodi eos iste itaque nulla quisquam! Iusto, perspiciatis, quisquam!</span>
                </p>
            </div>
            <div class="col-2" style="text-align: right; padding-right:3%; padding-top: 1%;">
                <a class="fas fa-pen" id="modify" href="#"></a>
                <a class="fas fa-trash" id="delete" href="#"></a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <img src="img/code.png" class="img-post">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <img src="img/code.png" class="img-post">
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Sauvegarder</button>
                    <button type="submit" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

</section>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
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

    function SubmitModalForm() {
        $.ajax({
            type: "POST",
            url: "scripts/post-treatment.php",
            data: {'idpost': $("#idpost-delete").val(), 'modal-delete-submit': true},
            success: function (data) {
                window.location.reload();
            }
        });
    }
</script>
</html>
