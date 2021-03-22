<?php
use app\model\Jobs;
use app\model\Employees;

$employees = Employees::findAll();
/** @var \app\model\Employees[] $employees */

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
                    <table class="table" id="employeeTable">
                        <thead>
                        <tr>
                            <th scope="col">
                                Vezetéknév
                            </th>
                            <th scope="col">
                                Keresztnév
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
                            <th scope="col">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td id="employee[lastName]" contenteditable></td>
                            <td id="employee[firstName]" contenteditable></td>
                            <td id="employee[email]" contenteditable></td>
                            <td id="employee[phone]" contenteditable></td>
                            <td id="employee[job]" contenteditable></td>
                            <td id="employee[address]" contenteditable></td>
                            <td>
                                <button type="submit" name="employeeAdd" id="employeeAdd" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr
                        <?php foreach($employees as $k => $v): ?>
                            <tr>
                                <td id="employee[lastName]" data-id1="<?= $v->getEmployeeId() ?>" contenteditable><?=$v->getLastName()?></td>
                                <td id="employee[firstName]" data-id2="<?= $v->getEmployeeId() ?>" contenteditable><?=$v->getFirstName()?></td>
                                <td id="employee[email]" data-id3="<?= $v->getEmployeeId() ?>" contenteditable><?=$v->getEmail()?></td>
                                <td id="employee[phoneNumber]" data-id4="<?= $v->getEmployeeId() ?>" contenteditable><?=$v->getPhoneNumber()?></td>
                                <td id="employee[jobId]" data-id5="<?= $v->getEmployeeId() ?>" contenteditable><?=Jobs::findOneById($v->getJobId())->getTitle()?></td>
                                <td id="employee[address]" data-id6="<?= $v->getEmployeeId() ?>" contenteditable><?=$v->getAddress()?></td>
                                <td>
                                    <button type="submit" name="employeeDel" id="employeeDel" class="btn btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>