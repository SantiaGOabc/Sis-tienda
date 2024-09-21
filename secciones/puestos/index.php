<!--LLAMAMOS A LA BASE DE DATOS, ../../ VOLVEMOS 2 CARPETAS ANTES-->
<?php
include("../../bd.php");

$sentencia = $conexion->prepare("SELECT * FROM `rol`");
$sentencia->execute();
$lista_roles = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>
<!--incluimos el header, ../ permite trepar a la otra carpeta-->
<?php include("../../templates/header.php");?>

Lista ROLES

<!-- boton para registrar datos-->
<!--bs5card -->
<div class="card">
    <div class="card-header">
<<!--Listado-->
    </div>
    <div class="card-body">
        <!-- poner tabla, bs5-table-->
        <div
            class="table-responsive-sm">
            <table class="table ">
                <thead>
                    <tr>
                        <!-- LISTAR DATOS-->
                        <th scope="col">ID</th>
                        <th scope="col">Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_roles as $registro) { ?>

                        <!--MOSTRAR EL LISTADO-->
                        <tr class="">
                            <td scope="row"><?php echo $registro['idrol'];?></td>
                            <td scope="row"><?php echo $registro['rol'];?></td>
                        </tr>
                        <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- incluimos el fuder-->
<?php include("../../templates/footer.php");?>