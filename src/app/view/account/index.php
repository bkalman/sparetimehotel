<?php //include('src/app/view/template/startTemplate.php'); ?>
<?php //include('src/app/view/template/navbar.php'); ?>
<section id="container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Belépés</h2>
                <form action="index.php?controller=user&action=login" id="login" method="post">
                    <table>
                        <tr>
                            <td>
                                <input type="text" name="email" id="email" placeholder="email" class="form-control">
                            </td>
                            <td>
                                <input type="password" name="password" id="password" placeholder="jelszó" class="form-control">
                            </td>
                            <td>
                                <input type="submit" value="Belépés" class="btn btn-success">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</section>
<?php //include('src/app/view/template/endTemplate.php'); ?>
