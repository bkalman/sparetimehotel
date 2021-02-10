<?php use app\model\Employees; ?>
<div id="nav">
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=hyperlink&action=home">Kezdőlap</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link" href="index.php?controller=hyperlink&action=attractions">Látnivalók</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=hyperlink&action=contacts">Elérhetőségek</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=hyperlink&action=employees">Belépés</a>
                </li>
                <li class="nav-item">
                    <div id="userlog">
                        <?php if(!is_null(Employees::getCurrentUser())): ?>
                            <table>
                                <tr>
                                    <td>
                                        <span class="navbar-text"><?= Employees::getCurrentUser()->getEmail() ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-danger" href="index.php?controller=employees&action=logout">Kilépés</a></span>
                                    </td>
                                </tr>
                            </table>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="title">
        <h1>Spare<br>Time<span>hotel</span></h1>
    </div>
</div>
<div id="house"></div>