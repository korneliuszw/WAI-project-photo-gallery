<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "commons/head.php" ?>
</head>
<body>
<?php include "commons/navbar.php" ?>

<?php include "commons/error.php" ?>

<form method="POST" action="/uploader" enctype="multipart/form-data" class="w-50 m-auto">
    <div class="form-group">
        <label for="file-upload">
            Image:
        </label>
        <input class="form-control-file" type="file" required name="file" id="file-upload">
    </div>
    <div class="form-group">
        <label for="watermark">
            Watermark:
        </label>
        <input class="form-control" type="text" name="watermark" required id="watermark">
    </div>
    <div class="form-group">
        <label for="author">
            Author:
        </label>
        <input class="form-control" id="author"
               name="author"
               type="text" <?php if (isset($model->username)) echo "value=\"$model->username\" disabled"; else echo "required" ?> />
    </div>
    <div class="form-group">
        <label for="title">
            Title:
        </label>
        <input class="form-control" id="title" name="title" type="text" required>
    </div>
    <?php if (isset($model->username)) { ?>
        <div class="form-group">
            <input class="form-check-input" type="radio" name="visibility" value="public" id="visibility-public"
                   checked>
            <label class="form-check-label" for="visibility-public">
                Public
            </label>
        </div>
        <div class="form-group">
            <input class="form-check-input" type="radio" name="visibility" value="private" id="visibility-private">
            <label class="form-check-label" for="visibility-private">
                Private
            </label>
        </div>
    <?php } ?>
    <button class="btn btn-primary mt-2" type="submit">Send</button>
</form>
</body>

</html>