<?php $title='Profil'; require_once 'includes/functions.php'; require_login(); $id=user()['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf();
 if(isset($_POST['podatki'])){ db()->prepare('UPDATE uporabniki SET ime=?,priimek=?,telefon=? WHERE id=?')->execute([trim($_POST['ime']),trim($_POST['priimek']),trim($_POST['telefon']),$id]); $_SESSION['user']['ime']=trim($_POST['ime']); flash('Podatki shranjeni.'); }
 else { $s=db()->prepare('SELECT geslo FROM uporabniki WHERE id=?'); $s->execute([$id]);
  if(!password_verify($_POST['staro'],$s->fetchColumn())) flash('Staro geslo ni pravilno.','err'); elseif(strlen($_POST['novo'])<8) flash('Novo geslo naj ima vsaj 8 znakov.','err');
  else { db()->prepare('UPDATE uporabniki SET geslo=? WHERE id=?')->execute([password_hash($_POST['novo'],PASSWORD_DEFAULT),$id]); flash('Geslo spremenjeno.'); } }
 redirect('profil.php'); }
include 'includes/header.php'; $s=db()->prepare('SELECT * FROM uporabniki WHERE id=?'); $s->execute([$id]); $u=$s->fetch(); ?>
<h1>Moj profil</h1><form class="box" method="post"><?=csrf()?><input type="hidden" name="podatki" value="1">Ime<input name="ime" value="<?=e($u['ime'])?>" required>Priimek<input name="priimek" value="<?=e($u['priimek'])?>" required>E-naslov<input value="<?=e($u['email'])?>" disabled>Telefon<input name="telefon" value="<?=e($u['telefon'])?>"><button>Shrani</button></form>
<h2>Sprememba gesla</h2><form class="box" method="post"><?=csrf()?>Staro geslo<input type="password" name="staro" required>Novo geslo<input type="password" name="novo" minlength="8" required><button>Spremeni geslo</button></form>
<?php include 'includes/footer.php'; ?>
