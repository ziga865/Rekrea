<?php $title='Objekti'; include 'includes/header.php';
$q=trim($_GET['q']??''); $k=(int)($_GET['k']??0); $w=["status='na_voljo'"]; $a=[];
if($q!==''){ $w[]='(o.naziv LIKE ? OR o.naslov LIKE ? OR o.opis LIKE ?)'; array_push($a,"%$q%","%$q%","%$q%"); }
if($k){ $w[]='o.kategorija_id=?'; $a[]=$k; }
$s=db()->prepare('SELECT o.*,k.naziv kat FROM sportni_objekti o JOIN kategorije_objektov k ON k.id=o.kategorija_id WHERE '.implode(' AND ',$w).' ORDER BY o.naziv'); $s->execute($a); $r=$s->fetchAll();
$kat=db()->query('SELECT * FROM kategorije_objektov')->fetchAll(); ?>
<div class="pagehead"><h1>Športni objekti</h1><p class="muted">Izberite prostor, ki ustreza vaši skupini in športu.</p></div><form class="inline filter" method="get"><input name="q" value="<?=e($q)?>" placeholder="Iskanje…"><select name="k"><option value="0">Vse kategorije</option><?php foreach($kat as $c): ?><option value="<?=$c['id']?>" <?=$k==$c['id']?'selected':''?>><?=e($c['naziv'])?></option><?php endforeach; ?></select><button>Filtriraj</button></form>
<div class="grid"><?php foreach($r as $o): ?><?=kartica($o)?><?php endforeach; if(!$r) echo '<p>Ni rezultatov.</p>'; ?></div>
<?php include 'includes/footer.php'; ?>
