<?php
// Zaženite enkrat: http://localhost/rekrea/install.php
$p=new PDO('mysql:host=localhost;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$p->exec("CREATE DATABASE IF NOT EXISTS REKREA CHARACTER SET utf8mb4 COLLATE utf8mb4_slovenian_ci; USE REKREA;
CREATE TABLE IF NOT EXISTS uporabniki(id INT AUTO_INCREMENT PRIMARY KEY,ime VARCHAR(50) NOT NULL,priimek VARCHAR(50) NOT NULL,email VARCHAR(120) NOT NULL UNIQUE,geslo VARCHAR(255) NOT NULL,telefon VARCHAR(30),vloga ENUM('uporabnik','admin') DEFAULT 'uporabnik',datum_registracije DATETIME DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS kategorije_objektov(id INT AUTO_INCREMENT PRIMARY KEY,naziv VARCHAR(80) NOT NULL,opis TEXT);
CREATE TABLE IF NOT EXISTS sportni_objekti(id INT AUTO_INCREMENT PRIMARY KEY,kategorija_id INT NOT NULL,naziv VARCHAR(120) NOT NULL,opis TEXT,naslov VARCHAR(200),kapaciteta INT DEFAULT 0,cena_na_uro DECIMAL(8,2) DEFAULT 0,status ENUM('na_voljo','ni_na_voljo') DEFAULT 'na_voljo',FOREIGN KEY(kategorija_id) REFERENCES kategorije_objektov(id));
CREATE TABLE IF NOT EXISTS rezervacije(id INT AUTO_INCREMENT PRIMARY KEY,uporabnik_id INT NOT NULL,objekt_id INT NOT NULL,datum DATE NOT NULL,ura_zacetka TIME NOT NULL,ura_konca TIME NOT NULL,status ENUM('cakajoca','potrjena','zavrnjena','preklicana') DEFAULT 'cakajoca',datum_ustvarjanja DATETIME DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(uporabnik_id) REFERENCES uporabniki(id) ON DELETE CASCADE,FOREIGN KEY(objekt_id) REFERENCES sportni_objekti(id) ON DELETE CASCADE);
CREATE TABLE IF NOT EXISTS delovni_cas(id INT AUTO_INCREMENT PRIMARY KEY,objekt_id INT NOT NULL,dan_v_tednu TINYINT NOT NULL,ura_od TIME NOT NULL,ura_do TIME NOT NULL,FOREIGN KEY(objekt_id) REFERENCES sportni_objekti(id) ON DELETE CASCADE);");
$p->exec('USE REKREA');
if(!$p->query('SELECT COUNT(*) FROM uporabniki')->fetchColumn()){
 $p->prepare("INSERT INTO uporabniki(ime,priimek,email,geslo,vloga) VALUES('Admin','Rekrea','admin@rekrea.si',?,'admin')")->execute([password_hash('admin123',PASSWORD_DEFAULT)]);
 $p->exec("INSERT INTO kategorije_objektov(naziv,opis) VALUES('Telovadnice','Šolske in društvene telovadnice'),('Športne dvorane','Velike večnamenske dvorane'),('Nogometna igrišča','Travnata in umetna igrišča'),('Teniška igrišča','Zunanja in notranja igrišča')");
 $p->exec("INSERT INTO sportni_objekti(kategorija_id,naziv,opis,naslov,kapaciteta,cena_na_uro) VALUES
 (1,'Telovadnica OŠ Center','Prostorna telovadnica z opremo za košarko, odbojko in rokomet.','Cankarjeva 5, Maribor',40,25),
 (2,'Dvorana Tabor','Večnamenska dvorana s tribunami.','Ulica Vita Kraigherja 3, Maribor',300,90),
 (3,'Nogometno igrišče Ljudski vrt','Igrišče z umetno travo in osvetlitvijo.','Ljudski vrt 1, Maribor',22,40),
 (4,'Teniško igrišče Pristan','Peščeno igrišče ob Dravi.','Pristan 10, Maribor',4,15)");
 $o=$p->query('SELECT id FROM sportni_objekti')->fetchAll(PDO::FETCH_COLUMN); $s=$p->prepare('INSERT INTO delovni_cas(objekt_id,dan_v_tednu,ura_od,ura_do) VALUES(?,?,?,?)');
 foreach($o as $id) for($d=1;$d<=7;$d++) $s->execute([$id,$d,$d<6?'08:00':'09:00',$d<6?'21:00':'18:00']);
}
echo 'Namestitev končana. <a href="index.php">Odpri Rekrea</a><br>Admin: admin@rekrea.si / admin123 (spremenite geslo!). Nato izbrišite install.php.';
