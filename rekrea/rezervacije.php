<?php $title='Rezervacija'; require_once 'includes/functions.php'; require_login();
$oid=(int)($_REQUEST['objekt']??0); $s=db()->prepare("SELECT * FROM sportni_objekti WHERE id=? AND status='na_voljo'"); $s->execute([$oid]); $o=$s->fetch(); if(!$o) redirect('objekti.php');
$datum=$_REQUEST['datum']??date('Y-m-d'); if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$datum)||$datum<date('Y-m-d')) $datum=date('Y-m-d');
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf(); $z=(int)$_POST['zacetek']; $d=max(1,min(4,(int)$_POST['trajanje'])); $sl=ure($oid,$datum); $ok=true;
 for($h=$z;$h<$z+$d;$h++) if(empty($sl[$h])) $ok=false;
 if($ok && prost($oid,$datum,sprintf('%02d:00:00',$z),sprintf('%02d:00:00',$z+$d))){
  db()->prepare('INSERT INTO rezervacije(uporabnik_id,objekt_id,datum,ura_zacetka,ura_konca) VALUES(?,?,?,?,?)')->execute([user()['id'],$oid,$datum,sprintf('%02d:00:00',$z),sprintf('%02d:00:00',$z+$d)]);
  flash('Rezervacija oddana in čaka na potrditev.'); redirect('moje_rezervacije.php'); }
 flash('Izbrani termin je že zaseden ali izven delovnega časa.','err'); }
include 'includes/header.php'; $sl=ure($oid,$datum); ?>
<h1>Rezervacija: <?=e($o['naziv'])?></h1>
<form class="inline" method="get"><input type="hidden" name="objekt" value="<?=$oid?>"><div>Datum<input type="date" id="datum" name="datum" value="<?=e($datum)?>"></div><button>Prikaži termine</button></form>
<div class="slots"><?php if(!$sl) echo '<p>Objekt ta dan ne obratuje.</p>'; foreach($sl as $h=>$f): ?><span class="slot <?=$f?'':'x'?>"><?=sprintf('%02d:00–%02d:00',$h,$h+1)?></span><?php endforeach; ?></div>
<?php $free=array_keys(array_filter($sl)); if($free): ?><form class="box" method="post"><?=csrf()?><input type="hidden" name="objekt" value="<?=$oid?>"><input type="hidden" name="datum" value="<?=e($datum)?>">
Začetna ura<select name="zacetek"><?php foreach($free as $h) echo "<option value=$h>".sprintf('%02d:00',$h).'</option>'; ?></select>
Trajanje<select name="trajanje"><?php for($i=1;$i<=4;$i++) echo "<option value=$i>$i h</option>"; ?></select><button>Oddaj rezervacijo</button></form><?php endif; ?>
<?php include 'includes/footer.php'; ?>
