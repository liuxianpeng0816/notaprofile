<?php 
require_once('config/config.php');
require_once('lib/class/NotAProfile.php');

//Cuando filtro es 1 se muestran las claimed, sino, las orphans.
$filtro = isset($_GET['filtro'])?$_GET['filtro']:0;
$llaves = $filtro==0? NotAProfile::darLlavesDisponibles() : NotAProfile::darLlavesReclamadas2();
$count = count($llaves);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>not_a_profile | Llaves</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="/css/estilos.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript">
	
	<?php foreach ($llaves as &$llave) {echo "var".$llave['id']."; ";} ?>

	function initialize() {
		var latlng = new google.maps.LatLng(4.620913,-74.083643);
		var myOptions = {zoom: 13, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
		var map = new google.maps.Map(document.getElementById("gmap"), myOptions);
		var visibleInfoWindow = null;

		// Definimos los iconos de las llaves
		var iconoNoReclamado = 'llave.png';

		//Agregamos las llaves
	
		<?php foreach ($llaves as &$llave) {
		    echo "	var posicionLlave".$llave['id']." = new google.maps.LatLng(".$llave['latitud'].", ".$llave['longitud']."); ";
		    $txt = substr($llave['txt'], 0, 50);$txt .= strlen($llave['txt'])>50 ? "...": "";
		    echo "	var contenidoLlave".$llave['id']." = '<b> Informacion de la llave: </b> <br /> <p>Esta llave tiene el ID: ".$llave['id']."  *** </p><br/><b>Texto:<b><p></p>".$txt."'; ";
		    echo "	var infoWindow".$llave['id']." = new google.maps.InfoWindow({content: contenidoLlave".$llave['id'].", maxWidth: 350 }); ";
		    echo "	llave".$llave['id']." = new google.maps.Marker({position: posicionLlave".$llave['id'].", map: map, icon: iconoNoReclamado }); ";
		    echo "	google.maps.event.addListener(llave".$llave['id'].", 'click', function() {if(visibleInfoWindow){ visibleInfoWindow.close(); } infoWindow".$llave['id'].".open(map,llave".$llave['id'].");  visibleInfoWindow = infoWindow".$llave['id']."; });\n";
	    }?> 
	}
	function mostrar(id){  google.maps.event.trigger(id, 'click');  }
	  
</script>
</head>
<body onload="initialize()">

	<div id="contenido">
		<div id="cabezote">
			<h1>not_a_profile</h1>
			<ul id="nav">
				<li><a href="#">keyring</a></li>
				<li><a href="#">connections</a></li>
			</ul>
			<div id="userinfo">
				rank: locksmith | <a href="#">log_out</a>
			</div>
		</div>
		
		<div id="cuerpo">
			<div id="llaves">
				<!--
				cambiar el class segun el tab activo:
				orphans: orphansactiva
				claimed: claimedactiva
				class = dinámico
				-->
				<?php if($filtro==1){$class ="claimedactiva";}else{$class ="orphansactiva";}?>
				<div id="mapatabs" class=<?php echo($class); ?>>
					<div class="orphans"><a href="?filtro=0">orphans</a></div>
					<div class="claimed"><a href="?filtro=1">claimed</a></div>					
				</div>
				<ul id="listallaves">
					<?php foreach ($llaves as &$llave) {
						echo "					<li id = llave".$llave['id']."a";?>
						<div class="fotollave">
							<!-- si la llave es mia o yo la reclame me sale la foto -->
							<!-- si no es mia o no la reclame me sale generica -->
							<?php if(NotAProfile::puedeSerVista($llave)){?>
								<img src=<?php if(isset($llave['foto'])){echo("photos/".$llave['foto']."_t.jpg");}else{echo("img/fotogenerica.gif");}?> width="61" height="61" />
							<?php }else{?>
								<img src="img/fotogenerica.gif" width="61" height="61" />
							<?php } // Termina if puedeSerVista?>
						</div>
						<div class="textollave">
							<!-- esto sale si la llave es MIA o YO LA RECLAME -->
							<?php if(NotAProfile::puedeSerVista($llave))
							{
								$txt = substr($llave['txt'], 0, 20);
								$txt .= strlen($llave['txt'])>20 ? "...": "";
							?>
							<span class="texto"><?php echo($txt)?></span><br />
							<span class="loc"><?php echo($llave['latitud'].",".$llave['longitud'])?> </span><br />
							<?php }?>
							<a href="javascript:void(0);" onclick= "mostrar(llave<?php echo $llave['id']; ?>);"> view </a>
						</div>
					</li>
					<?php } // Termina foreach?>
				</ul>
			</div>
			
		<div id="mapa">
			<div id="navmapa">
				<a class="createkey" href="insertarLlave.php"><span>create_a_key</span></a>
			</div>
			<div id="gmap">
				
			</div>
		</div>
	
	
		</div>
		<div id="pie">
			<a href="#">help</a> | <a href="#">about this project</a> | <a href="#">google code</a> | <a href="#">copyright notice</a>
		</div>
	</div>
</div>
</body>
</html>


