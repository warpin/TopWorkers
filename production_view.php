<!doctype html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <title>Сводка ежедневной выработки сборочного участка</title>
    <meta name="description" content="Рейтинг вязальщиков">
    <link rel="stylesheet" href="css/style.css">
    <META HTTP-EQUIV="refresh" CONTENT="420">

</head>

<body>
<h1 class="top_h1" align="center">Сводка ежедневной выработки сборочного участка ООО "ПРЕТТЛЬ-НК"</h1>


    <?php
        header('Content-Type: text/html; charset=utf-8', true);

        $month_array=array("январь","феварль","март","апрель","май","июнь","июль","август","сентябрь",
            "октябрь","ноября","декабрь");
        date_default_timezone_set('UTC');
        $mounth=$month_array[date("n")-1];
        $day=date("j");
        $year=date("Y");
        echo '<h1 align="center">по заявкам на '.$mounth.' '.$year.' в часах</h1>';
    ?>

    <div class="top-form">
        <?php
            require './config/db_connect.php';
            $sql = "SELECT * FROM customers";
            if ($res = $pdo->query($sql)) {
                //Пройдемся по контрагентам
                echo '<table align="left" style="width: 100%; border: hidden" >';
                foreach($pdo->query($sql) as $row){
                    echo '<tr>';
                    echo '<td>';
                    $id_customer=$row['id'];
                    echo '<h2 align="center">'.$row['name'].'</h2>';

                    $result=false;
                    $INTERVAL=1;
                    while ($result==false)  {

                        $sql_select_production_data = "SELECT frv_plan FROM production WHERE
                        id_customer=$id_customer and date = CURDATE() - INTERVAL $INTERVAL DAY ";
                        /*try{
                            foreach($res_select = $pdo->query($sql_select_production_data) as $row_production_data) {
                            }
                        }catch(Exception $e) {
                            echo $sql_select_production_data;
                            echo 'Exception -> ';
                            var_dump($e->getMessage());
                        }*/
                        $res_select = $pdo->query($sql_select_production_data);
                        if($res_select->fetchColumn() == 0){
                            $INTERVAL++;
                            if($INTERVAL>15)$result=true;

                        } else {
                            $result=true;
                        }

                    }


                    //выцепим данные с контрагента за 2 недели
                    try {
                        $sql_select_production_data = "SELECT * FROM production WHERE
                        id_customer=$id_customer and
                        date between CURDATE() - INTERVAL $INTERVAL DAY and CURRENT_DATE + INTERVAL 15  DAY
                        ORDER BY date ASC";

                        echo '<table align="center" border="0">';
                        echo '<tr>';
                        echo '<td>Дата</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data) {
                            $dateSrc = $row_production_data["date"];
                            $dateTime = date_create( $dateSrc);
                            if($row_production_data["dayoff"]==0)echo '<td style="background-color:greenyellow">' . date_format($dateTime,"d.m") . '</td>';
                            else echo '<td style="background-color:red">' . date_format($dateTime,"d.m") . '</td>';
                        }
                        echo '</tr>';
                        /*echo '<tr>';
                        echo '<td>Кол-во человек/КТУ</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['workers_number'] . '</td>';
                        echo '</tr>';
                        */

                        echo '<tr>';
                        echo '<td>План ФРВ</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['frv_plan'] . '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>Факт</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['fact'] . '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>ФРВ по потребности</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['frv_demand'] . '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>Факт выроботка</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['fact_production'] . '</td>';
                        echo '</tr>';

                        echo '<tr>';
                        echo '<td>Факт людей в часах</td>';
                        foreach($pdo->query($sql_select_production_data) as $row_production_data)
                            echo '<td>' . $row_production_data['fact_workers_in_hour'] . '</td>';
                        echo '</tr>';
                        echo '</table>';
                        echo '<br>';
                    }catch(Exception $e) {
                        echo $sql_select_production_data;
                        echo 'Exception -> ';
                        var_dump($e->getMessage());
                    }

                }
                echo '</td>';
                echo '</tr>';
                echo '</table>';

            }
        ?>
        <hr>
        <p>Разработал: Булатов Р.Р.</p>

    </div>

</body>
</html>


