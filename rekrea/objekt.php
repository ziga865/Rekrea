<?php $title='Objekt'; include 'includes/header.php';
$s=db()->prepare('SELECT o.*,k.naziv kat FROM sportni_objekti o JOIN kategorije_objektov k ON k.id=o.kategorija_id WHERE o.id=?'); $s->execute([(int)($_GET['id']??0)]); $o=$s->fetch();
if(!$o){ echo '<p>Objekt ne obstaja.</p>'; include 'includes/footer.php'; exit; }
$dc=db()->prepare('SELECT * FROM delovni_cas WHERE objekt_id=? ORDER BY dan_v_tednu'); $dc->execute([$o['id']]); $dni=['','Ponedeljek','Torek','Sreda','Četrtek','Petek','Sobota','Nedelja']; ?>
<div class="banner c<?=crc32($o['kat'])%4?>"><span class="big"><?=ikona($o['kat'])?></span><div><span class="tag"><?=e($o['kat'])?></span><h1><?=e($o['naziv'])?></h1><p>📍 <?=e($o['naslov'])?></p></div></div>
<div class="card detail"><p><?=nl2br(e($o['opis']))?></p><p><b>Lokacija:</b> <?=e($o['naslov'])?> · <a target="_blank" href="https://www.google.com/maps/search/<?=urlencode($o['naslov'])?>">Zemljevid</a><br><b>Kapaciteta:</b> <?=$o['kapaciteta']?> oseb<br><b>Cena:</b> <?=number_format($o['cena_na_uro'],2,',','.')?> €/uro</p>
<h3>Delovni čas</h3><?php foreach($dc as $d) echo $dni[$d['dan_v_tednu']].': '.substr($d['ura_od'],0,5).' – '.substr($d['ura_do'],0,5).'<br>'; ?>
<p><a class="btn" href="rezervacije.php?objekt=<?=$o['id']?>">Rezerviraj termin</a></p></div>
<?php include 'includes/footer.php'; ?>
