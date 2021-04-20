<section id="container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Belépés</h2>
            </div>
        </div>
        <form id="login" method="post">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <?= !empty($_GET['login']) && $_GET['login'] == 'false' ? '<p style="color:red;text-align:center;">Sikertelen bejelentkezés!</p>' : '' ?>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="email" id="email" placeholder="email" class="form-control my-2">
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="password" id="password" placeholder="jelszó" class="form-control my-2">
                        </div>
                        <div class="col-md-4">
                            <input type="submit" value="Belépés" class="btn btn-success my-2">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>