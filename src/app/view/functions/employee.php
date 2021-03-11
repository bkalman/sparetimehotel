<?php use app\model\Jobs; ?>

<?php if(Jobs::currentUserCan('function.employees')): ?>
<h1>Munkavállalók</h1>
<?php endif; ?>