<?php use app\model\Jobs; ?>

<?php if(Jobs::currentUserCan('function.reports')): ?>
<h1>Hibabejelentések</h1>
<?php endif; ?>