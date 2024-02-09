<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Gallery</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/saved">Saved images</a>
            </li>
            <?php if ($model->loggedIn) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Register</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="/uploader">Post photos</a>
            </li>
            <div class="search nav-item" role="search"/>
        </ul>


    </div>
</nav>