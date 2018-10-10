<?php 
require_once "../modelos/Gestion.php";

$gestion=new Gestion();

$idgestion=isset($_POST["idgestion"])? limpiarCadena($_POST["idgestion"]):"";
$nombregestion=isset($_POST["nombregestion"])? limpiarCadena($_POST["nombregestion"]):"";
$slug=isset($_POST["slug"])? limpiarCadena($_POST["slug"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idgestion)){
			$rspta=$gestion->insertar($nombregestion,$slug);
			echo $rspta ? "Gestion registrada" : "La Gestion no se pudo registrar";
		}
		else {
			$rspta=$gestion->editar($idgestion,$nombregestion,$slug);
			echo $rspta ? "Gestion actualizada" : "La Gestion no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$gestion->desactivar($idgestion);
 		echo $rspta ? "Gestion Desactivada" : "Gestion no se puede desactivar";
 		break;
	break;

	case 'activar':
		$rspta=$gestion->activar($idgestion);
 		echo $rspta ? "Gestion activada" : "La Gestion no se puede activar";
 		break;
	break;

	case 'mostrar':
		$rspta=$gestion->mostrar($idgestion);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case 'listar':
		$rspta=$gestion->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idgestion.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idgestion.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idgestion.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idgestion.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombregestion,
 				"2"=>$reg->slug,
 				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
}
?>