<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <title>Рейтинг вязальщиков ООО "ПРЕТТЛЬ-НК"</title>
    <meta name="description" content="Рейтинг вязальщиков">
    <link rel="stylesheet" href="css/style.css">
    <META HTTP-EQUIV="refresh" CONTENT="420">

</head>

<body>

<h1 align="left"><a href="http://top.prettl.ru/manage_workers.php" class="h1link">Администрирование работников</a></h1>
<h1 align="left"><a href="http://top.prettl.ru/manage_score.php" class="h1link">Управление баллами работников</a></h1>
<h1 align="left"><a href="http://top.prettl.ru/get_score_on_date.php" class="h1link">Просмотр баллов на дату</a></h1>


<h1 class="top_h1" align="center">Рейтинг персонала производства жгутов ООО "ПРЕТТЛЬ-НК"</h1>



    <?php
        header('Content-Type: text/html; charset=utf-8', true);

        $month_array=array("Января","Феварля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября",
            "Октября","Ноября","Декабря");
        date_default_timezone_set('UTC');
        if(isset($_GET['date']))$date=$_GET['date'];
        else $date=null;

        if(is_null($date)){
            $mounth=$month_array[date("n")-1];
            $day=date("j");
            $year=date("Y");
        } else {
            $mounth=$month_array[date("n",strtotime($date))-1];
            $day=date("j",strtotime($date));
            $year=date("Y",strtotime($date));
        }
        echo '<h1 align="center">на '.$day.' '.$mounth.' '.$year.'</h1>';
    ?>

    <div class="top-form">
        <form method="GET">
            <label>Выберите дату
                <input type="date" name="date" required value="<?php echo $date ?>">
            </label>

            <button name="pick_date" value="true" class="btn">
                Отобразить баллы
            </button>
            <p></p>
        </form>
        <?php

        require './config/db_connect.php';


        $sql = "SELECT SUM(main.score),main.date,workers.fio
        FROM main
        LEFT OUTER JOIN workers ON main.user_id=workers.id
        WHERE MONTH(date) = MONTH('$date') and YEAR(date) = YEAR('$date')
        GROUP BY workers.fio
        ORDER BY SUM(main.score) DESC";


        $prev_score=0;
        $prev_count=1;
        if ($res = $pdo->query($sql)) {
            /* Определим количество строк, подходящих под условия выражения SELECT */
            if ($res->fetchColumn() > 0) {

                $class="p_top_gold";
                $count=1;

                echo '<table align="center" border="0">';

                $new_table=true;

                foreach($pdo->query($sql) as $row){
                //while ($row = $res->fetchAll(PDO::FETCH_ASSOC)){

                    if($new_table){
                        echo '<td>';
                        echo '<table align="center">';
                        echo '<tr>';
                        echo '<td>Место</td>';
                        echo '<td>ФИО Работника</td>';
                        echo '<td>Баллы</td>';
                        echo '</tr>';

                        $new_table=false;
                    }

                    echo '<tr>';
                    //echo '<td class="'.$class.'">'.$count.'</td>';
                    if($prev_score==$row["SUM(main.score)"] && $row["SUM(main.score)"]!=0 )echo '<td class="'.$class.'">'.$prev_count.'</td>';
                    else {
                        $prev_count=$count;
                        $prev_score=$row["SUM(main.score)"];

                        if($prev_count>3)$class="p_top_silver";
                        if($prev_count>10)$class="p_top_bronze";
                        if($prev_count>20)$class="p_top_simple";

                        echo '<td class="'.$class.'">'.$count.'</td>';

                    }

                    echo '<td class="'.$class.'">'.$row["fio"].'</td>';
                    echo '<td class="'.$class.'">'.$row["SUM(main.score)"].'</td>';
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
                //echo '</marquee>';
            } else {
                // no data found
                echo "Пока еще нет данных";
            }
        }
        ?>

</div>

</body>
</html>

