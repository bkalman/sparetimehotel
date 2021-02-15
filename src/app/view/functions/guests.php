<?php use app\model\Jobs; ?>

<?php if(Jobs::currentUserCan('function.guests')): ?>
<h1>Vendégek</h1>
<?php endif; ?>