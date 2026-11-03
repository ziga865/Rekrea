<?php $title='Prijava'; require_once 'includes/functions.php';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $s=db()->prepare('SELECT * FROM uporabniki WHERE email=?'); $s->execute([trim($_POST['email'])]); $u=$s->fetch();
 if($u&&password_verify($_POST['geslo'],$u['geslo'])){ session_regenerate_id(true); unset($u['geslo']); $_SESSION['user']=$u; redirect($u['vloga']==='admin'?'admin/index.php':'index.php'); }
 flash('Napačen e-naslov ali geslo.','err'); }
include 'includes/header.php'; ?><h1>Prijava</h1><form class="box" method="post"><?=csrf()?>E-naslov<input type="email" name="email" required>Geslo<input type="password" name="geslo" required><button>Prijava</button></form><?php include 'includes/footer.php'; ?>
