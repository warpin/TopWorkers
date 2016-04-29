<!DOCTYPE html>
<html lang="ru" xmlns:table-layout="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Управление баллами</title>
</head>
<body>
<div class="login-form">
    <h1 align="left"><a href="http://top.prettl.ru/manage_workers.php" class="h1link">Администрирование работников</a></h1>
    <h1 align="left"><a href="http://top.prettl.ru/manage_score.php" class="h1link">Управление баллами работников</a></h1>

    <?php

    //if(!isset($_GET['date']) || empty($_GET['date']))$date=date("Y-m-d");
        if(isset($_GET['date']))$date=$_GET['date'];
        else $date=null;
        //else $date=date("Y-m-d");
        //else $date=$_GET['date'];
    ?>

    <form method="GET">
        <label>Выберите дату
            <input type="date" name="date" required value="<?php echo $date ?>">
        </label>

        <button name="pick_date" value="true" class="btn">
            Отобразить баллы
        </button>
        <p></p>


    <?php

    require './config/db_connect.php';

    $count=1;
    if(!empty($_GET["date"])){
        $date=$_GET["date"];
        //echo $date;
        //echo '<form method="GET">';
        $sql = "SELECT * FROM workers";
        if ($res = $pdo->query($sql)) {
            while ($row = $res->fetch()){
                $user_id=$row['id'];
                $sql_select="SELECT * FROM main WHERE user_id='$user_id' and date='$date'";
                if ($res_select = $pdo->query($sql_select)) {
                    if ($res_select->fetchColumn() == 0) {
                        $sql_insert = "INSERT INTO main(user_id,date,score) VALUES ('$user_id','$date','0')";
                        $res_insert = $pdo->exec($sql_insert);
                    }
                }
            }
        }

        $sql = "SELECT *
        FROM main
        LEFT OUTER JOIN workers ON main.user_id=workers.id
        WHERE date='$date'
        ORDER BY workers.fio ASC";

        if ($res = $pdo->query($sql)) {
            if($res->fetchColumn() > 0){
                $new_table=true;
                echo '<table align="center" border="0">';
                $count=1;
                while ($row = $res->fetch()){

                    if($new_table){
                        echo '<td >';
                        echo '<table align="center" class="table_top">';
                        echo '<tr>';
                        echo '<td >Место</td>';
                        echo '<td>ФИО Работника</td>';
                        echo '<td>Баллы</td>';
                        echo '</tr>';

                        $new_table=false;
                    }
                    echo '<tr>';


                    //$user_ids[$count]=$row["user_id"];
                    echo '<tr>';
                    echo('<td><span>'.$count.'</span></td>');
                    echo('<td><span>'.$row['fio'].'</span></td>');
                    //echo('<td><span class="table_loc">'.$row["score"].'</span></td>');
                    echo('<td><span><input name=score'.$row['user_id'].' type="number" min="0" max="100" value="'.$row['score'].'"/></span></td>');
                    echo '</tr>';


                    if(is_int($count/20)){
                        echo '</table>';
                        echo '</td>';
                        $new_table=true;
                    }
                    $count++;
                }
                echo '</table>';
                echo '</table>';


                echo '<button name="save" value="true" class="btn" type="submit">';
                echo 'Сохранить';
                echo '</button>';
                echo '</form>';

                echo '<p>Баллы на дату: '.date("d.m.Y", strtotime($date)).' </p>';
            }




        } else {
            // no data found
            echo "No data found";
        }
    }
    if(isset($_GET['save'])){
        if($_GET['save']=="true"){
            $err=false;
            $sql = "SELECT * FROM workers";
            if ($res = $pdo->query($sql)) {
                while ($row = $res->fetch()){
                    //Берем построчно пользователя
                    $user_id=$row['id'];
                    $user_fio=$row['fio'];
                    $score_index="score".$user_id;
                    if(isset($_GET[$score_index])){
                        $score=$_GET[$score_index];
                        $sql_select="SELECT * FROM main WHERE user_id='$user_id' and date='$date'";
                        //echo mysql_num_rows($result_select)." ";
                        //Если нет строк
                        $res_select = $pdo->query($sql_select);
                        if($res_select->fetchColumn() == 0){
                            //echo $score.' ';
                            //То добавляем в главную таблицу
                            //$result_insert = mysql_query("INSERT INTO main(user_id,date,score) VALUES ('$user_ids[$i]','$date','$score')");
                            $sql_insert = "INSERT INTO main(user_id,date,score) VALUES ('$user_id','$date','$score')";
                            if (!$res_insert = $pdo->exec($sql_insert)) {
                                echo '<p>Ошибка добавления для пользователя: ' . $user_fio . '</p>';
                                $err = true;
                            }
                            //Иначе обновим поле очки
                        } else {
                            //echo $score.' ';
                            //$result_update = mysql_query("UPDATE main SET score='$score' where user_id='$user_ids[$i]' and date='$date'");
                            $sql_update = "UPDATE main SET score='$score' where user_id='$user_id' and date='$date'";
                            if(!$res_update = $pdo->query($sql_update)){
                                echo '<p>Ошибка обновления для пользователя :'.$user_fio.'</p>';
                                $err=true;
                            }

                        }
                    }
                }
            }

            if(!$err){
                //header( "Location: http://top.prettl.ru/manage_score.php?date=".$date);;
                echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_score.php?date='.$date.'&pick_date=true&save=success">';
            }
        }
        if($_GET['save']=="success"){
            echo '<p>Успешно сохранено</p>';
        }
        $user_ids=null;
        $count=1;
    }else {
        $count--;
        echo '<p>Успешно загружено работников: '.$count.'</p>';
    }

    ?>

</div>

</body>


</html>