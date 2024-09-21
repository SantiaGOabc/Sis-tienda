<?php 
$sqlDistribuidor = "Select * from distribuidor";
$sqlREsulatdo=$conn->query($sqlDistribuidor);
$dis = [];
if($sqlREsulatdo->num_rows>0){
    while($row = $sqlREsulatdo->FETCH_ASSOC()){
        $dis[]=$row['nombre'];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTES DISTRIBUIDORES</title>
    <script> src="https://cdn.jsdelivr.net/npm/chart.js"</script>
</head>
<body>
    
</body>
</html>