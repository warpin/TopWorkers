<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <title>Рейтинг вязальщиков ООО "ПРЕТТЛЬ-НК"</title>
    <meta name="description" content="Рейтинг вязальщиков">
    <link rel="stylesheet" href="./css/style_list.css">
    <META HTTP-EQUIV="refresh" CONTENT="420">

</head>

<body>
<div class="top-form">
    <h1 class="top_h1" align="center">Рейтинг персонала производства жгутов ООО "ПРЕТТЛЬ-НК"</h1>
    <?php
        header('Content-Type: text/html; charset=utf-8', true);

        $month_array=array("Января","Феварля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября",
            "Октября","Ноября","Декабря");
        date_default_timezone_set('UTC');
        $mounth=$month_array[date("n")-1];
        $day=date("j");
        $year=date("Y");
        echo '<h1 align="center">на '.$day.' '.$mounth.' '.$year.'</h1>';
    ?>


        <?php

        require './config/db_connect.php';


        $sql = "SELECT SUM(main.score),main.date,workers.fio
        FROM main
        LEFT OUTER JOIN workers ON main.user_id=workers.id
        WHERE MONTH(date) = MONTH(CURRENT_DATE())
        GROUP BY workers.fio
        ORDER BY SUM(main.score) DESC";


        if ($res = $pdo->query($sql)) {
            /* Определим количество строк, подходящих под условия выражения SELECT */
            if ($res->fetchColumn() > 0) {
                // check for empty result
                $class="p_top_gold";
                $count=1;
                echo '<marquee behavior="scroll" direction="up" scrollamount="5" height="100%">';
                //while ($row = mysql_fetch_array($result)) {
                while ($row = $res->fetch()){
                    if($count>3)$class="p_top_silver";
                    if($count>10)$class="p_top_bronze";
                    if($count>20)$class="p_top_simple";
                    //echo('<p class="'.$class.'">'.$count.' место: </p>');
                    echo('<p class="'.$class.'">'.$count.' место: '.$row["fio"].' '.$row["SUM(main.score)"].' балл(ов)</p>');
                    $count++;
                }
                echo '</marquee>';
            } else {
                // no data found
                echo "No data found";
            }

        }
        ?>

</div>




</body>
</html>

