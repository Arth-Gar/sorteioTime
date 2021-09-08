<html>
<head></head>
<body>   
<div style="max-width:600px; margin:0 auto; text-align: center">
<h1>Sorteio de jogadores</h1>
<h2>Jogadores a confirmar</h2>

<form name="confirmar jogadores" method="post" action="/index.php"> 
    <label>Confirme os jogadores</label><br>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost", "root", "", "datateam");
$jogadores = $mysqli->query("SELECT nome FROM jogadores;");
if ($jogadores->num_rows > 0) {
  // output data of each row
  while($row = $jogadores->fetch_assoc()) {
    echo "<label>Nome: " . $row["nome"]."</label>". '<input type = "checkbox" id = "confirmarj" name = "'. $row["nome"].'" value = "'. $row["nome"].'">' ;        
  }
} 
if(!empty($_POST['confirmar'])){
  
}   
?>
    <input type="submit" name="confirmar" value="confirmar jogadores" />
</form>
<h2>Time 1</h2>
<form name="sorteio" method="post" action="/index.php"> 
    <label>Insira o limite de jogadores em campo por time (sem contar com o goleiro)</label><br>
    <!--<input id="limite" type="number" name="limite">-->
    <select name="limite" id="limite">
     <option value="1">1</option>
     <option value="2">2</option>
     <option value="3">3</option>
     <option value="4">4</option>
    </select>
    <input type="submit" name="submit" value="SORTEAR TIME" />
    
</form>

<?php

if(!empty($_POST['submit'])){
    if (!empty($_POST['limite'])) {$limite=$_POST['limite'];};
}

//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//$mysqli = new mysqli("localhost", "root", "", "datateam");
$result = $mysqli->query("DROP TABLE IF EXISTS temp_cs");
$result = $mysqli->query("CREATE TABLE temp_cs SELECT id, nome, nivel, goleiro FROM jogadores WHERE goleiro=0 ORDER BY RAND() LIMIT ".$limite.";");
$result2 = $mysqli->query("SELECT jogadores.nome, jogadores.nivel, jogadores.goleiro FROM jogadores LEFT JOIN temp_cs ON jogadores.nome = temp_cs.nome WHERE jogadores.goleiro=0 and temp_cs.nome IS NULL ORDER BY RAND() LIMIT ".$limite." ;");
$result = $mysqli->query("select nome, nivel, goleiro FROM temp_cs;");
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<br> Jogador: " . $row["nome"]. ", <strong>nível " . $row["nivel"]. "</strong>, Joga na posição: "; 
            if ($row["goleiro"]==0) {
                echo "Linha". "<br>";
                
            }
            else {
                echo "Goleiro";
            };            
  }
  $result = $mysqli->query("insert into temp_cs SELECT id, nome, nivel, goleiro FROM jogadores WHERE goleiro=1 ORDER BY RAND() LIMIT 1;");
  $goleiro = $mysqli->query("SELECT nome, nivel, goleiro FROM temp_cs WHERE goleiro=1;");
  $goleiro2 = $mysqli->query("SELECT jogadores.nome, jogadores.nivel, jogadores.goleiro FROM jogadores LEFT JOIN temp_cs ON jogadores.nome = temp_cs.nome WHERE jogadores.goleiro=1 and temp_cs.nome IS NULL ORDER BY RAND() LIMIT 1; ");

  while($row = $goleiro->fetch_assoc()) {
    echo "<br>Nome: " . $row["nome"]. ", <strong>nível " . $row["nivel"]. "</strong>, Joga na posição: "; 
            if ($row["goleiro"]==0) {
            echo "Linha". "<br>";}
            else {echo "Goleiro";};            
  }
  
            }
else {
  if(isset($_POST['submit'])){
      echo "0 results";
  }
}echo"<br><h2>Time 2</h2>";
if ($result2->num_rows > 0) {
  // output data of each row
  while($row = $result2->fetch_assoc()) {
    echo "<br>Nome: " . $row["nome"]. ", <strong>nível " . $row["nivel"]. "</strong>, Joga na posição: "; 
            if ($row["goleiro"]==0) {
                echo "Linha". "<br>";
                
            }
            else {
                echo "Goleiro";
            };            
};
  while($row = $goleiro2->fetch_assoc()) {
    echo "<br>Nome: " . $row["nome"]. ", <strong>nível " . $row["nivel"]. "</strong>, Joga na posição: "; 
            if ($row["goleiro"]==0) {
            echo "Linha". "<br>";}
            else {echo "Goleiro";};            
  }
            };
$result = $mysqli->query("DROP TABLE temp_cs");
mysqli_close($mysqli);
?>

</div>
</body> 