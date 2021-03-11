<?php
use app\model\Jobs;
use app\model\ErrorReports;

$reports = ErrorReports::findAll();
/** @var ErrorReports[] $reports */
if(Jobs::currentUserCan('function.report')): ?>
    <section id="container">
        <div class="container">
            <?php if(!empty($_GET['delete']) && $_GET['delete'] == 'true'): ?>
                <div class="row">
                    <div class="col-12">
                        <h3 style="color:green;">Sikeres befejezés!</h3>
                    </div>
                </div>
            <?php elseif(!empty($_GET['delete']) && $_GET['delete'] == 'false'): ?>
                <div class="row">
                    <div class="col-12">
                        <h3 style="color:red;">Sikertelen befejezés! Próbálja meg újból!</h3>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <h1>Munkavállalók</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">
                                Emelet
                            </th>
                            <th scope="col"">
                                Hely
                            </th>
                            <th scope="col"">
                                Szobaszám
                            </th>
                            <th scope="col">
                                Státusz
                            </th>
                            <th scope="col">
                                Üzenet
                            </th>
                            <th scope="col" style="width: 40px">
                                Kész?
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($reports as $k => $v): ?>
                            <tr>
                                <form action="index.php?controller=errorReport&action=delete" method="post">
                                    <input type="hidden" name="reportId" value="<?=$v->getReportId()?>">
                                    <td><?=is_null($v->getStorey()) ? 'földszint' : $v->getStorey()?></td>
                                    <td><?=$v->getPlace()?></td>
                                    <td><?=$v->getRoomId()?></td>
                                    <td><?=$v->getStatus()?></td>
                                    <td><?=$v->getReport()?></td>
                                    <td>
                                        <button type="submit" class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </form>
                            </tr
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>