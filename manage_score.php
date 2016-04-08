<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/html" xmlns:table-layout="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <meta charset="cp-1251">
    <title>Управление баллами</title>
</head>
<body>
<div class="login-form">
    <h1 align="left"><a href="http://top.prettl.ru/manage_workers.php" class="h1link">Администрирование работников</a></h1>
    <h1 align="left"><a href="http://top.prettl.ru/manage_score.php" class="h1link">Управление баллами работников</a></h1>

    <?php
        //if(!isset($_GET['date']) || empty($_GET['date']))$date=date("Y-m-d");
        if(isset($_GET['date']))$date=$_GET['date'];
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


    <table align="center" >
        <tr>
            <td class="table_manage" >№</td>
            <td class="table_manage" >ФИО работника</td>
            <td class="table_manage" >Баллы</td>
        </tr>


    <?php

    require './config/db_connect.php';
    $conn = new DB_CONNECT();
    $db = new DB_CONNECT();


    $count=1;
    if(!empty($_GET["date"])){
        $date=$_GET["date"];
        //echo $date;
        //echo '<form method="GET">';
        $result = mysql_query("SELECT * FROM workers");
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $user_id=$row['id'];
                $result_select=mysql_query("SELECT * FROM main WHERE user_id='$user_id' and date='$date'");
                if(mysql_num_rows($result_select) == 0)$result_insert = mysql_query("INSERT INTO main(user_id,date,score) VALUES ('$user_id','$date','0')");
            }
        }

        $result = mysql_query("SELECT *
        FROM main
        LEFT OUTER JOIN workers ON main.user_id=workers.id
        WHERE date='$date'
        ORDER BY workers.fio ASC");



        if (!empty($result) and mysql_num_rows($result) > 0) {
            // check for empty result



            while ($row = mysql_fetch_array($result)) {
                //$user_ids[$count]=$row["user_id"];
                echo '<tr>';
                echo('<td><span class="table_loc">'.$count.'</span></td>');
                echo('<td><span class="table_loc">'.$row["fio"].'</span></td>');
                //echo('<td><span class="table_loc">'.$row["score"].'</span></td>');
                echo('<td><span class="table_loc"><input name=score'.$row["user_id"].' type="number" min="0" max="100" value="'.$row["score"].'"/></span></td>');
                echo '</tr>';
                $count++;
            }



            echo '<button name="save" value="true" class="btn" type="submit">';
            echo 'Сохранить';
            echo '</button>';
            echo '</form>';

            echo '<p>Баллы на дату: '.date("d.m.Y", strtotime($date)).' </p>';


        } else {
            // no data found
            echo "No data found";
        }
    }
    if(isset($_GET['save'])){
        if($_GET['save']=="true"){
            $err=false;

            $result = mysql_query("SELECT * FROM workers");
            if($result){
                while ($row = mysql_fetch_array($result)) {
                    //Берем построчно пользователя
                    $user_id=$row['id'];
                    $score_index="score".$user_id;
                    if(isset($_GET[$score_index])){
                        $score=$_GET[$score_index];
                        $result_select=mysql_query("SELECT * FROM main WHERE user_id='$user_id' and date='$date'");
                        //echo mysql_num_rows($result_select)." ";
                        //Если нет строк
                        if(mysql_num_rows($result_select) == 0){
                            //echo $score.' ';
                            //То добавляем в главную таблицу
                            //$result_insert = mysql_query("INSERT INTO main(user_id,date,score) VALUES ('$user_ids[$i]','$date','$score')");
                            $result_insert = mysql_query("INSERT INTO main(user_id,date,score) VALUES ('$user_id','$date','$score')");
                            if(!$result_insert) {
                                echo '<p>Ошибка сохранения для пользователя с ИД:' . $user_id . '</p>';
                                $err = true;
                            }
                            //Иначе обновим поле очки
                        } else {
                            //echo $score.' ';
                            //$result_update = mysql_query("UPDATE main SET score='$score' where user_id='$user_ids[$i]' and date='$date'");
                            $result_update = mysql_query("UPDATE main SET score='$score' where user_id='$user_id' and date='$date'");
                            if(!$result_update){
                                echo '<p>Ошибка сохранения для пользователя с ИД:'.$user_id.'</p>';
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

