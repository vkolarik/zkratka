<?php
if($_SERVER['REQUEST_URI'] == "/"){ header('Location: ./a'); } //REDIRECT FROM zkra.tk TO zkra.tk/a

require("theassets/config.php"); //Load config and connect to DB

$banned = array("193.151.12.100");
if(in_array($clientip, $banned)){
	echo "Fuck you Ukraine"; exit;
}

require('./theassets/cookie.php');

$req = addslashes(str_replace("a", "", $_GET['request']));

if($req == ""){
	$blank = true;
}else{
	$query = mysqli_query($db,"SELECT type, author, count, absolute, cropped, timeout FROM records WHERE address = \"$req\"");
	$record = mysqli_fetch_array($query, MYSQLI_ASSOC);
	if($record['type'] == 2){
		$target = $record['absolute'];
		//LOG
		mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"redirected\", \"$uri\", \"$target\")");
		mysqli_query($db, "UPDATE records SET last_shown=$timestamp, count=count+1 WHERE address = \"$req\"");
		header("Location: $target");
	}elseif($record['type'] == null){
		$blank = true;
		$notfound = true;
		//LOG
		mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"visited_blank\", \"\", \"\")");
	}elseif($record['type'] == 1){
		mysqli_query($db, "UPDATE records SET count = count + 1, last_shown=\"$timestamp\" WHERE address = \"$req\"");
		if($record['author'] == $clientid){
			if($record['cropped'] == 0){
				$letcrop = true;
			}
			$image = true;
			$ownimage = true;
			$imgabsolute = $record['absolute'];
			//LOG
			mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"visit_own\", \"$uri\", \"\")");
		}else{
			$image = true;
			$imgabsolute = $record['absolute'];
			//LOG
			mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"visit_shared\", \"$uri\", \"\")");
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" >
	<title>ZKRA.TK/A | Nejrychlejší PrintScreen | URL zkracovač</title>
	<link rel="stylesheet" href="theassets/style.css" />
	<link rel="icon" href="theassets/favicon.png" />
	<!-- FRAMEWORKS -->
	<script src="theassets/jquery.js" ></script>
	<script src="theassets/bootstrap.js" ></script>
	<script src="theassets/modal.js" ></script>
	<script src="theassets/start.js" type="text/javascript" ></script>
	<script src="theassets/clipboard.js" ></script>
	<script type="text/javascript" src="theassets/jquery-pack.js"></script>
	<script type="text/javascript" src="theassets/jquery.imgareaselect.min.js"></script>
	<script type="text/javascript" src="theassets/ajax.js"></script>
	<!-- Custom -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//analytics.nitramak.eu/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '4']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//analytics.nitramak.eu/piwik.php?idsite=4&rec=1" style="border:0;" alt="" /></p></noscript>
</head>
<body>
<div class="container content">
    	<div class="jumbotron" align="center" style="padding-top:20px;padding-bottom:20px;margin-top: 10px" >
			<h1><a href="https://zkra.tk/a" >ZKRA.TK/A</a></h1>
			<p>PrintScreen | Kratší URL</p>
		</div>

<!-- CTRLV FORM -->
<form method="POST" enctype="multipart/form-data" action="save.php" id="formular">
    <input type="hidden" name="img_val" id="img_form" value="" required />
    <input type="hidden" name="size_val" id="size_form" value="" required />
    <input type="hidden" name="type_val" id="type_form" value="" required />
</form>

<?php
if($blank){ //==================================================== BLANK ===============================================================================?>

<div class="panel panel-default">
	<div class="panel-heading" align="center" >
		<div class="btn-group" >
			<a id="img_btn" class="btn btn-primary" >PrintScreen (CTRL+V)</a>
			<a id="upload_btn" class="btn btn-default" >Upload ze souboru</a>
			<a id="link_btn" class="btn btn-default" >Zkracovač URL</a>
		</div>
	</div>
	<div class="panel-body" align="center" >
		<div id="main" >
		<div id="main_img" style="display:block" >
			<h3>NO IMAGE</h3>
			Stiskni PrintScreen a potom CTRL + V
		</div>
		<div id="main_upload" style="display:none" >
			<h3>Nahrát obrázek ze souboru</h3>
			Vyber soubor
			<form id="upload_form" method="post" enctype="multipart/form-data" action="upload.php" >
				<input type="hidden" name="upload_size" id="upload_size">
				<input type="file" name="imgfile" id="imgfile" accept="image/*" >
			</form>
		</div>
		<div id="main_link" style="display:none" >
			<h3>Zkracovač URL</h3>
			<style>
				#link_submit {
					display: inline-block;
					margin-bottom: 0;
					font-weight: normal;
					text-align: center;
					vertical-align: middle;
					cursor: pointer;
					padding: 10px 18px;
					font-size: 15px;
					color: white;
					border: 1px solid transparent;
					background-color: #2780e3;
					border-color: #2780e3;
				}
			</style>
			<form id="link_form" >
				<div class="input-group">
    				<input type="text" name="url" id="url" class="form-control" placeholder="https://.....">
    				<div class="input-group-btn">
      					<input id="link_submit" value="Zkrátit" type="submit" />
    				</div>
				</div>
				<!--<input type="text" name="url" id="url">
				<input type="submit" id="link_submit" value="Zkrátit" />-->
				<br><br>
				<div id="link_response"></div>
			</form>
		</div>
	</div>
</div>
<?php
}elseif($image ){
	if($ownimage && $letcrop){ //==================================================== OWN IMAGE, CAN CROP ===============================================================================?>

<script src="theassets/cut.js" type="text/javascript" ></script>
<div class="panel panel-default">
	<div class="panel-heading"><div align="center" >Vlastní obrázek (ořez povolen) | <?php echo $record['count']+1; ?> zobrazení | Ponechat na serveru (po posledním zobrazení)
			<input id="sel_address" type="hidden" name="address" value="<?php echo $req; ?>" />
			<select name="value" class="timeoutselect">
				<option value="10" <?php echo ($record['timeout'] == 10 ? "selected='selected'" : ""); ?>>10 minut</option>
				<option value="24" <?php echo ($record['timeout'] == 24 ? "selected='selected'" : ""); ?>>hodina</option>
				<option value="1" <?php echo ($record['timeout'] == 1 ? "selected='selected'" : ""); ?>>den</option>
				<option value="7" <?php echo ($record['timeout'] == 7 ? "selected='selected'" : ""); ?>>týden</option>
				<option value="30" <?php echo ($record['timeout'] == 30 ? "selected='selected'" : ""); ?>>měsíc</option>
				<option value="365" <?php echo ($record['timeout'] == 365 ? "selected='selected'" : ""); ?>>rok</option>
				<option value="999" <?php echo ($record['timeout'] == 999 ? "selected='selected'" : ""); ?>>navždy</option>
			</select>
			<br>
			<input id="foo" type="text" value="https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"><button class="btn" data-clipboard-target="#foo"><img src="theassets/clippy.svg" alt="Kopírovat do schránky" title="Kopírovat do schránky" width="15px" height="20px" ></button>
	</div></div>
	<div class="panel-body" align="center" >
		<p><b>TIP:</b> Obrázek můžete ořezat vybráním oblasti a kliknutím na tlačítko "Ořezat".</p>
		<!--<span id="state" ></span>-->
	</div>
</div>
</div>
<div align="center">
	<img src="<?php echo $imgabsolute; ?>" id="thumbnail" alt="Create Thumbnail" style="cursor:crosshair" />
	<form name="thumbnail" action="crop.php" method="post">
		<input type="hidden" name="x1" value="" id="x1" />
		<input type="hidden" name="y1" value="" id="y1" />
		<input type="hidden" name="x2" value="" id="x2" />
		<input type="hidden" name="y2" value="" id="y2" />
		<input type="hidden" name="w" value="" id="w" />
		<input type="hidden" name="h" value="" id="h" />
		<input type="hidden" name="path" value="<?php echo str_replace("https://i.zkra.tk", "/var/www/izkratk", $record['absolute']); ?>" id="path" />
		<input type="hidden" name="return" value="<?php echo "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" id="return" />
		<br>
		<input type="submit" name="upload" value="Ořezat" id="save_thumb" />
	</form>
</div>
<?php
	}else{ //==================================================== IMAGE ===============================================================================?>
<div class="panel panel-default">
	<div class="panel-heading"><div align="center" ><?php if($ownimage == true){ echo "Vlastní obrázek"; }else{ echo "Sdílený obrázek"; } ?> | <?php echo $record['count']+1; ?> zobrazení
		<?php
		if($ownimage){   //==========LET CHANGE TIMEOUT============================ ?>
			| Ponechat na serveru (po posledním zobrazení)
			<input id="sel_address" type="hidden" name="address" value="<?php echo $req; ?>" />
			<select name="value" class="timeoutselect">
				<option value="10" <?php echo ($record['timeout'] == 10 ? "selected='selected'" : ""); ?>>10 minut</option>
				<option value="24" <?php echo ($record['timeout'] == 24 ? "selected='selected'" : ""); ?>>hodina</option>
				<option value="1" <?php echo ($record['timeout'] == 1 ? "selected='selected'" : ""); ?>>den</option>
				<option value="7" <?php echo ($record['timeout'] == 7 ? "selected='selected'" : ""); ?>>týden</option>
				<option value="30" <?php echo ($record['timeout'] == 30 ? "selected='selected'" : ""); ?>>měsíc</option>
				<option value="365" <?php echo ($record['timeout'] == 365 ? "selected='selected'" : ""); ?>>rok</option>
				<option value="999" <?php echo ($record['timeout'] == 999 ? "selected='selected'" : ""); ?>>navždy</option>
			</select>
<?php	}	?>
			<br>
			<input id="foo" type="text" value="https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"><button class="btn" data-clipboard-target="#foo"><img src="theassets/clippy.svg" alt="Kopírovat do schránky" title="Kopírovat do schránky" width="15px" height="20px" ></button>
	</div></div>
	<div class="panel-body" align="center" >
			<span id="state" ></span>
			<?php echo "<a href='$imgabsolute' target=_blank ><img style='max-width:100%;max-height:100%;width:auto;height:auto' src='$imgabsolute' /></a>"; ?>
	</div>
</div>
<?php
	}
} ?>

<!-- /container -->
<?php if(!$letcrop){ echo "</div>";}?>
<!-- Modal -->
<div id="popup" class="modal-dialog modal-sm fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="popupmsg" >Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Custom -->
<script src="theassets/end.js" type="text/javascript" ></script>
</body>
</html>