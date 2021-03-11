<?php
use app\model\Jobs;
use app\model\Employees;

$employees = Employees::findAll();
/** @var \app\model\Employees[] $em */
if(Jobs::currentUserCan('function.employee')): ?>
    <section id="container">
        <div class="container">
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
                                Név
                            </th>
                            <th scope="col"">
                                E-mail
                            </th>
                            <th scope="col"">
                                Telefonszám
                            </th>
                            <th scope="col">
                                Munkakör
                            </th>
                            <th scope="col">
                                Lakcím
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($employees as $k => $v): ?>
                            <tr>
                                <td scope="row"><?=$v->getLastName()?> <?=$v->getFirstName()?></td>
                                <td><?=$v->getEmail()?></td>
                                <td><?=$v->getPhoneNumber()?></td>
                                <td><?=Jobs::findOneById($v->getJobId())->getTitle()?></td>
                                <td><?=$v->getAddress()?></td>
                            </tr
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>