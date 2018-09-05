<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
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
                    <input type="file" class="form-control-file" name="picture-post[]" accept="image/png, image/jpeg, image/gif" multiple>
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                    <input type="submit" class="btn btn-primary" name="submit-post" value="Publier">
                </div>
            </div>
        </div>
    </form>
    <div class="row" id="post">
        <div class="col-12">
            <p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis ipsum, mollitia. Ad, aperiam doloremque earum et excepturi facilis, ipsa minima nesciunt nobis pariatur porro quo rerum sint ullam ut voluptate!</span><span>Adipisci aperiam at autem consectetur consequuntur culpa cumque dolore dolorum eum ex explicabo fugit harum iusto modi molestiae natus neque nihil possimus praesentium, provident quibusdam quo soluta suscipit tenetur ullam.</span><span>Accusantium commodi consequuntur dolore, doloremque expedita id illum laborum nam natus, nihil nisi porro quo ratione sint sunt! Ad beatae debitis doloribus esse itaque minus perferendis quam tenetur vel veniam?</span><span>Ducimus eius, ex incidunt nesciunt odit quam repellendus ullam. Blanditiis eius error, ipsam iusto nisi, non obcaecati optio qui ratione sapiente sint voluptas! Aliquam, nulla, odit? Dignissimos eveniet expedita minus!</span><span>Beatae dolores ea in laborum quia. Cum cupiditate eum non porro reiciendis ullam voluptatibus. Accusantium consectetur explicabo odit totam. A asperiores commodi eos iste itaque nulla quisquam! Iusto, perspiciatis, quisquam!</span>
            </p>
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</html>