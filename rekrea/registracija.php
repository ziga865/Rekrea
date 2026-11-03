<?php $title='Registracija'; require_once 'includes/functions.php';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $i=trim($_POST['ime']);$p=trim($_POST['priimek']);$m=trim($_POST['email']);$t=trim($_POST['telefon']);$g=$_POST['geslo'];
 if(!$i||!$p||!filter_var($m,FILTER_VALIDATE_EMAIL)||strlen($g)<8) flash('Izpolnite vsa polja; geslo naj ima vsaj 8 znakov.','err');
 else { $c=db()->prepare('SELECT 1 FROM uporabniki WHERE email=?'); $c->execute([$m]); if($c->fetch()) flash('Ta e-naslov je že registriran.','err');
  else { db()->prepare('INSERT INTO uporabniki(ime,priimek,email,geslo,telefon) VALUES(?,?,?,?,?)')->execute([$i,$p,$m,password_hash($g,PASSWORD_DEFAULT),$t]); flash('Registracija uspešna. Prijavite se.'); redirect('prijava.php'); } } }
include 'includes/header.php'; ?><h1>Registracija</h1><form class="box" method="post"><?=csrf()?>Ime<input name="ime" required>Priimek<input name="priimek" required>E-naslov<input type="email" name="email" required>Telefon<input name="telefon">Geslo (min. 8 znakov)<input type="password" name="geslo" minlength="8" required><button>Registriraj se</button></form><?php include 'includes/footer.php'; ?>
