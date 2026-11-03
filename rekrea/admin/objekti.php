<?php $title='Objekti'; require_once '../includes/functions.php'; require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $a=$_POST['a'];
 if($a==='del'){ db()->prepare('DELETE FROM sportni_objekti WHERE id=?')->execute([(int)$_POST['id']]); flash('Objekt izbrisan.'); }
 else { $v=[(int)$_POST['kat'],trim($_POST['naziv']),trim($_POST['opis']),trim($_POST['naslov']),(int)$_POST['kap'],(float)$_POST['cena'],$_POST['status']==='ni_na_voljo'?'ni_na_voljo':'na_voljo'];
  if($a==='add'){ db()->prepare('INSERT INTO sportni_objekti(kategorija_id,naziv,opis,naslov,kapaciteta,cena_na_uro,status) VALUES(?,?,?,?,?,?,?)')->execute($v); $id=db()->lastInsertId(); $s=db()->prepare('INSERT INTO delovni_cas(objekt_id,dan_v_tednu,ura_od,ura_do) VALUES(?,?,?,?)'); for($d=1;$d<=7;$d++) $s->execute([$id,$d,'08:00','21:00']); }
  else { $v[]=(int)$_POST['id']; db()->prepare('UPDATE sportni_objekti SET kategorija_id=?,naziv=?,opis=?,naslov=?,kapaciteta=?,cena_na_uro=?,status=? WHERE id=?')->execute($v); }
  flash('Objekt shranjen.'); }
 redirect('admin/objekti.php'); }
include '_nav.php'; $kat=db()->query('SELECT * FROM kategorije_objektov')->fetchAll(); $o=db()->query('SELECT o.*,k.naziv kat FROM sportni_objekti o JOIN kategorije_objektov k ON k.id=o.kategorija_id ORDER BY o.id')->fetchAll();
$ed=null; if(isset($_GET['uredi'])) foreach($o as $x) if($x['id']==$_GET['uredi']) $ed=$x; ?>
<h1>Športni objekti</h1><form class="box" method="post"><?=csrf()?><input type="hidden" name="a" value="<?=$ed?'edit':'add'?>"><input type="hidden" name="id" value="<?=$ed['id']??0?>"><h3><?=$ed?'Uredi objekt':'Dodaj objekt'?></h3>
Naziv<input name="naziv" value="<?=e($ed['naziv']??'')?>" required>Kategorija<select name="kat"><?php foreach($kat as $k) echo '<option value="'.$k['id'].'" '.(($ed['kategorija_id']??0)==$k['id']?'selected':'').'>'.e($k['naziv']).'</option>'; ?></select>
Opis<textarea name="opis"><?=e($ed['opis']??'')?></textarea>Naslov<input name="naslov" value="<?=e($ed['naslov']??'')?>">Kapaciteta<input type="number" name="kap" value="<?=e($ed['kapaciteta']??0)?>">Cena na uro (€)<input type="number" step="0.01" name="cena" value="<?=e($ed['cena_na_uro']??0)?>">
Status<select name="status"><option value="na_voljo">Na voljo</option><option value="ni_na_voljo" <?=($ed['status']??'')==='ni_na_voljo'?'selected':''?>>Ni na voljo</option></select><button>Shrani</button></form>
<div class="tw"><table><tr><th>Naziv</th><th>Kategorija</th><th>€/h</th><th>Status</th><th></th></tr><?php foreach($o as $x): ?><tr><td><?=e($x['naziv'])?></td><td><?=e($x['kat'])?></td><td><?=$x['cena_na_uro']?></td><td><?=$x['status']?></td><td><a class="btn gray" href="?uredi=<?=$x['id']?>">Uredi</a> <form method="post"><?=csrf()?><input type="hidden" name="a" value="del"><input type="hidden" name="id" value="<?=$x['id']?>"><button class="red" data-confirm="Izbrišem objekt in njegove rezervacije?">Izbriši</button></form></td></tr><?php endforeach; ?></table></div>
<?php include '../includes/footer.php'; ?>
