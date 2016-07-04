<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <title>Учет затрат на качество</title>
    <meta name="description" content="Рейтинг вязальщиков">
    <link rel="stylesheet" href="css/style.css">
    <META HTTP-EQUIV="refresh" CONTENT="420">

</head>

<body>
<h1 class="top_h1" align="center">Учет затрат на качество ООО "ПРЕТТЛЬ-НК"</h1>


    <?php


        header('Content-Type: text/html; charset=utf-8', true);

        $month_array=array("января","феварля","марта","апреля","мая","июня","июля","августа","сентября",
            "октябрь","ноября","декабрь");
        /*date_default_timezone_set('UTC');
        $mounth=$month_array[date("n")-1];
        $day=date("j");
        $year=date("Y");*/
        //echo '<h1 align="center"> на '.$day.' '.$mounth.' '.$year.'</h1>';

        require './config/db_connect.php';
        $select_date = "SELECT * FROM quality ORDER BY date ASC LIMIT 1";
        foreach($pdo->query($select_date) as $row) {
            $date=$row['date'];

        }
        $mounth=$month_array[date("n",strtotime($date))-1];
        $day=date("j",strtotime($date));
        $year=date("Y",strtotime($date));
        //$newDate = date("d Y", strtotime($date));
        echo '<h1 align="center"> на '.$day.' '.$mounth.' '.$year.'</h1>';
    ?>

    <div class="top-form">
        <?php

            echo '<table align="center" border="0">';
            echo '<tr>';
            echo '<td>№</td>';
            echo '<td>Фамилия И.О.</td>';
            echo '<td>Должность</td>';
            echo '<td>Наименование жгута</td>';
            echo '<td>Отказ</td>';
            echo '<td>Количество</td>';
            echo '<td>Адрес</td>';
            echo '<td>Ответственный</td>';
            echo '</tr>';


            $count=1;
            $sql = "SELECT * FROM quality WHERE date='$date'";
            foreach($pdo->query($sql) as $row) {


                echo '<tr>';
                echo '<td>'.$count.'</td>';
                echo '<td>'.$row['fio'].'</td>';
                echo '<td>'.$row['position'].'</td>';
                echo '<td>'.$row['cable'].'</td>';
                echo '<td>'.$row['description'].'</td>';
                echo '<td>'.$row['quantity'].'</td>';
                echo '<td>'.$row['address'].'</td>';
                echo '<td>'.$row['responsible'].'</td>';


                echo '</tr>';

                $count=$count+1;

            }
            echo '</table>';
        ?>
        <hr>
        <p>Разработал: Булатов Р.Р.</p>

    </div>

</body>
</html>


