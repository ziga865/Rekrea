<?php $title='Domov'; include 'includes/header.php';
$izb=db()->query("SELECT o.*,k.naziv kat FROM sportni_objekti o JOIN kategorije_objektov k ON k.id=o.kategorija_id WHERE status='na_voljo' ORDER BY o.id DESC LIMIT 3")->fetchAll();
$kat=db()->query('SELECT * FROM kategorije_objektov')->fetchAll(); $n=db()->query('SELECT COUNT(*) FROM sportni_objekti')->fetchColumn(); ?>
<section class="hero"><div class="blob b1"></div><div class="blob b2"></div><div class="hin"><span class="pill">⚡ Rezervacija v nekaj klikih</span><h1>Najdi prostor.<br>Rezerviraj termin.<br><em>Igraj.</em></h1><p>Telovadnice, dvorane in igrišča na enem mestu. Preglejte proste termine in rezervirajte brez klicanja in čakanja.</p>
<form class="search" action="objekti.php" method="get"><span>🔍</span><input name="q" placeholder="Kaj iščete? Telovadnica, nogomet, Maribor…"><button>Išči</button></form>
<div class="hstats"><div><b><?=$n?>+</b>objektov</div><div><b>24/7</b>spletna rezervacija</div><div><b>0 €</b>registracija</div></div></div></section>
<h2 class="sec">Kategorije</h2><div class="cats"><?php foreach($kat as $k): ?><a class="cat" href="objekti.php?k=<?=$k['id']?>"><span><?=ikona($k['naziv'])?></span><b><?=e($k['naziv'])?></b></a><?php endforeach; ?></div>
<h2 class="sec">Izbrani objekti</h2><div class="grid"><?php foreach($izb as $o) echo kartica($o); ?></div>
<h2 class="sec">Kako deluje?</h2><div class="steps"><div><i>1</i><h3>Izberite objekt</h3><p>Poiščite po imenu, lokaciji ali kategoriji.</p></div><div><i>2</i><h3>Izberite termin</h3><p>Takoj vidite proste in zasedene ure.</p></div><div><i>3</i><h3>Rezervirajte</h3><p>Oddajte rezervacijo, administrator jo potrdi.</p></div></div>
<section class="cta"><h2>Pripravljeni na igro?</h2><p>Ustvarite brezplačen račun in rezervirajte prvi termin.</p><a class="btn light" href="registracija.php">Ustvari račun</a></section>
<?php include 'includes/footer.php'; ?>
