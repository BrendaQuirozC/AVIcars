<?php
/**
 * User: Brenda Quiroz
 * Date: 03/03/2018
 * Time: 10:11 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$nombeCuenta="";
$padre="";
if(isset($_POST["padre"]))
{
    $padre= $_POST["padre"];
}
if(isset($_POST["nombre"]))
{
    $nombeCuenta= $_POST["nombre"];
}
?>
<div class="modal-dialog top-modal">
    <div class="modal-content login-modal col-xs-12">
        <div class="modal-header modal-header-background">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Garage</h4>
        </div>
        <div class="modal-body" id="modHoja">
            <input type="radio" value="name" name="hoja" data-garage="<?= $nombeCuenta ?>" data-padre="<?=$padre?>" onclick="editGarage($(this))">&ensp;Actualizar nombre
            <br>
            <input type="radio" value="borra" name="hoja" data-garage="<?= $nombeCuenta ?>" data-padre="<?=$padre?>" onclick="editGarage($(this))">&ensp;Borrar
        </div>
        
    </div>
</div>