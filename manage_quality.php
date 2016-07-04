<!DOCTYPE html>
<html lang="ru" xmlns:table-layout="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Управление качеством</title>
</head>
<body>
    <h1 align="left"><a href="http://top.prettl.ru/manage_production.php" class="h1link">Управление выроботкой</a></h1>
    <h1 align="left"><a href="http://top.prettl.ru/manage_quality.php" class="h1link">Управление качеством</a></h1>
    <div class="login-form">

    <?php
        require './config/db_connect.php';


        if(isset($_GET['date']))$date=$_GET['date'];
        else $date=null;


        echo '<form method="GET">';
        echo '<label>Выберите дату';
        echo '<input type="date" name="date" required value="'.$date.'">';
        echo '</label>';
        echo '<button class="btn">';
        echo 'Отобразить данные по качеству';
        echo '</button>';
        echo '<p></p>';

        echo '<table align="left" border="0">';
        /*echo '<tr>';
        echo '<td>Кол-во человек/КТУ</td><td><input name="workers_number" type="text" min="0" value="'.$workers_number.'"></td></>';
        echo '</tr>';*/
        echo '<tr>';
        echo '<td>Фамилия И.О.</td><td><input size="35" name="afio" ></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Должность</td><td><input size="35" name="aposition" ></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Наименование жгута</td><td><input size="35"  name="acable"></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Описание дефекта</td><td><input size="35"  name="adescription" ></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Количество</td><td><input size="35"  name="aquantity" type="number" min="0" ></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Адрес</td><td><input size="35"  name="aaddress" ></td></>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Ответственный</td><td><input size="35"  name="aresponsible" value="Бикмурзина Алевтина Геннадьевна"></td></>';
        echo '</tr>';
        echo '</table>';
        echo '<button class="btn" name="Add" value="true">';
        echo 'Добавить';
        echo '</button>';

        echo '<hr>';

        if(isset($_GET['Delete'])) {
            if (isset($_GET['id'])) $id = $_GET['id'];
            try {
                $sql = "DELETE FROM quality where id=$id";
                if (!($pdo->exec($sql)))  {
                    echo "<p>Ошибка удаления</p>";
                }

            } catch (Exception $e) {
                echo $sql;
                echo 'Exception -> ';
                var_dump($e->getMessage());
            }
            //echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_quality.php?date='.$date.'">';
        }

        if(isset($_GET['Add'])){

            if(isset($_GET['afio']))$fio=$_GET['afio'];
            if(isset($_GET['aposition']))$position=$_GET['aposition'];
            if(isset($_GET['acable']))$cable=$_GET['acable'];
            if(isset($_GET['adescription']))$description=$_GET['adescription'];
            if(isset($_GET['aquantity']))$quantity=$_GET['aquantity'];
            if(isset($_GET['aaddress']))$address=$_GET['aaddress'];
            if(isset($_GET['aresponsible']))$responsible=$_GET['aresponsible'];

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            try {
                $sql_insert = "INSERT INTO quality
                                (date,fio,position,cable,description,quantity,address,responsible)
                                VALUES ('$date','$fio','$position','$cable','$description',$quantity,'$address','$responsible')";

                $res_insert = $pdo->exec($sql_insert);
                echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_quality.php?date='.$date.'">';
            }catch(Exception $e) {
                echo $sql_insert;
                echo 'Exception -> ';
                var_dump($e->getMessage());
            }


        }
        if(isset($_GET['Save'])) {

            if (isset($_GET['fio'])) $fio = $_GET['fio'];
            if (isset($_GET['position'])) $position = $_GET['position'];
            if (isset($_GET['cable'])) $cable = $_GET['cable'];
            if (isset($_GET['description'])) $description = $_GET['description'];
            if (isset($_GET['quantity'])) $quantity = $_GET['quantity'];
            if (isset($_GET['address'])) $address = $_GET['address'];
            if (isset($_GET['responsible'])) $responsible = $_GET['responsible'];
            if (isset($_GET['id'])) $id = $_GET['id'];

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            try {
                $sql_update = "UPDATE quality SET
                                fio='$fio',
                                position='$position',
                                cable='$cable',
                                description='$description',
                                quantity=$quantity,
                                address='$address',
                                responsible='$responsible'
                                where id=$id and date='$date'";
                $res_update = $pdo->query($sql_update);
                echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_quality.php?date='.$date.'">';
            } catch (Exception $e) {
                echo $sql_update;
                echo 'Exception -> ';
                var_dump($e->getMessage());
            }
        }

        if(isset($_GET['date'])){
            $sql = "SELECT * FROM quality WHERE date='$date'";
            if ($res = $pdo->query($sql)) {
                if ($res->fetchColumn() > 0) {
                    if ($res = $pdo->query($sql)) {
                        foreach ($pdo->query($sql) as $row) {
                            //$workers_number=$row['workers_number'];
                            //$date=$row[''];
                            $id=$row['id'];
                            $fio=$row['fio'];
                            $position=$row['position'];
                            $cable=$row['cable'];
                            $description=$row['description'];
                            $quantity=$row['quantity'];
                            $address=$row['address'];
                            $responsible=$row['responsible'];

                            echo '<p>Отображены данные за: '.$date.'</p>';
                            //echo '<form method="GET">';
                            echo '<table align="left" border="0">';
                            /*echo '<tr>';
                            echo '<td>Кол-во человек/КТУ</td><td><input name="workers_number" type="text" min="0" value="'.$workers_number.'"></td></>';
                            echo '</tr>';*/
                            echo '<tr>';
                            echo '<td>Фамилия И.О.</td><td><input size="35" name="fio" value="'.$fio.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Должность</td><td><input size="35" name="position" value="'.$position.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Наименование жгута</td><td><input size="35"  name="cable" value="'.$cable.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Описание дефекта</td><td><input size="35"  name="description" value="'.$description.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Количество</td><td><input size="35"  name="quantity" type="number" min="0" value="'.$quantity.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Адрес</td><td><input size="35"  name="address" value="'.$address.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Ответственный</td><td><input size="35"  name="responsible" value="'.$responsible.'"></td></>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>ID</td><td><input size="35" readonly name="id" value="'.$id.'"></td></>';
                            echo '</tr>';
                            echo '</table>';
                            echo '<button class="btn" name="Save" value="true">';
                            echo 'Сохранить';
                            echo '</button>';
                            echo '<button class="btn" name="Delete" value="true">';
                            echo 'Удалить';
                            echo '</button>';
                            //echo '</form>';
                            echo '<hr>';
                        }
                    }
                } else {
                    $fio="";
                    $position="";
                    $cable="";
                    $description="";
                    $quantity="0";
                    $address="";
                    $responsible="Бикмурзина Алевтина Геннадьевна";
                }
            }

        }
        echo '</form>';
        echo '<p>Разработал: Булатов Р.Р.</p>';
    ?>

</div>

</body>


</html>