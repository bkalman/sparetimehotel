<?php use app\model\Jobs; ?>

<?php if(Jobs::currentUserCan('function.restaurant')): ?>
<h1>Étterem</h1>
<?php endif; ?>