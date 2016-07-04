<!DOCTYPE html>
<html lang="ru" xmlns:table-layout="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Управление выроботкой</title>
</head>
<body>
    <h1 align="left"><a href="http://top.prettl.ru/manage_production.php" class="h1link">Управление выроботкой</a></h1>
    <h1 align="left"><a href="http://top.prettl.ru/manage_quality.php" class="h1link">Управление качеством</a></h1>
    <div class="login-form">

    <?php
        require './config/db_connect.php';


        if(isset($_GET['date']))$date=$_GET['date'];
        else $date=null;
        if(isset($_GET['customer']))$selected=$_GET['customer'];
        else $selected=null;


        $customers_array_ids=array();
        $customers_array_names=array();
        $sql = "SELECT * FROM customers ORDER BY id ASC";
        if ($res = $pdo->query($sql)) {
            foreach($pdo->query($sql) as $row){
                //while ($row = $res->fetch()){
                array_push($customers_array_names, $row['name']);
                array_push($customers_array_ids, $row['id']);
            }
        }

        echo '<form method="GET">';
        echo '<label>Выберите дату';
        echo '<input type="date" name="date" required value="'.$date.'">';
        echo '</label>';
        echo '<label>Выберите контрагента';
        echo '<select id="mySelect" name="customer" required>';
        foreach($customers_array_ids as $cc){
            if($selected==$cc)echo '<option value="' . $cc . '" id="'.$cc.'" selected>' . $customers_array_names[$cc-1] . '</option>';
            else echo '<option value="' . $cc . '" id="'.$cc.'">' . $customers_array_names[$cc-1] . '</option>';
        }

        echo '</select>';
        echo '</label>';
        echo '<button class="btn">';
            echo 'Отобразить данные по выроботке';
        echo '</button>';
        echo '<p></p>';



        if(isset($_GET['date']) && isset($_GET['customer'])){
            $id_customer=$_GET['customer'];
            $sql = "SELECT * FROM production WHERE date='$date' and id_customer='$id_customer'";
            if ($res = $pdo->query($sql)) {
                if ($res->fetchColumn() > 0) {
                    if ($res = $pdo->query($sql)) {
                        foreach ($pdo->query($sql) as $row) {
                            //$workers_number=$row['workers_number'];
                            $frv_plan=$row['frv_plan'];
                            $fact=$row['fact'];
                            $frv_demand=$row['frv_demand'];
                            $fact_production=$row['fact_production'];
                            $fact_workers_in_hour=$row['fact_workers_in_hour'];
                            $dayoff=$row['dayoff'];
                        }
                    }
                } else {
                    //$workers_number=0;
                    $frv_plan=0;
                    $fact=0;
                    $frv_demand=0;
                    $fact_production=0;
                    $fact_workers_in_hour=0;
                    $dayoff=0;
                }
            }

            if(isset($_GET['Save'])){

                //if(isset($_GET['workers_number']))$workers_number=$_GET['workers_number'];
                if(isset($_GET['frv_plan']))$frv_plan=$_GET['frv_plan'];
                if(isset($_GET['fact']))$fact=$_GET['fact'];
                if(isset($_GET['frv_demand']))$frv_demand=$_GET['frv_demand'];
                if(isset($_GET['fact_production']))$fact_production=$_GET['fact_production'];
                if(isset($_GET['fact_workers_in_hour']))$fact_workers_in_hour=$_GET['fact_workers_in_hour'];
                if(isset($_GET['dayoff'])){
                    if($_GET['dayoff']=="off")$dayoff=0;
                    else $dayoff=1;
                } else $dayoff=0;

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                $sql = "SELECT * FROM production WHERE date='$date' and id_customer='$id_customer'";
                if ($res = $pdo->query($sql)) {
                    if ($res->fetchColumn() > 0) {
                        try {
                            $sql_update = "UPDATE production SET
                            frv_plan='$frv_plan',
                            fact='$fact',
                            frv_demand='$frv_demand',
                            fact_production='$fact_production',
                            fact_workers_in_hour='$fact_workers_in_hour',
                            dayoff=$dayoff
                            where id_customer=$id_customer and date='$date'";
                            $res_update = $pdo->query($sql_update);
                        }catch(Exception $e) {
                            echo $sql_update;
                            echo 'Exception -> ';
                            var_dump($e->getMessage());
                        }
                    }else{
                        try {
                            $sql_insert = "INSERT INTO production
                            (date,frv_plan,fact,frv_demand,fact_production,fact_workers_in_hour,id_customer,dayoff)
                            VALUES ('$date','$frv_plan','$fact','$frv_demand','$fact_production','$fact_workers_in_hour',$id_customer,$dayoff)";

                            $res_insert = $pdo->exec($sql_insert);
                        }catch(Exception $e) {
                            echo $sql_insert;
                            echo 'Exception -> ';
                            var_dump($e->getMessage());
                        }
                    }
                }
            }

            echo '<p>Отображены данные за: '.$date.'</p>';
            if ($dayoff==0)echo '<p>Выходной день<input type="checkbox" name="dayoff" ></p>';
            else echo '<p>Выходной день<input type="checkbox" name="dayoff" checked></p>';
            echo '<table align="left" border="0">';
            /*echo '<tr>';
            echo '<td>Кол-во человек/КТУ</td><td><input name="workers_number" type="text" min="0" value="'.$workers_number.'"></td></>';
            echo '</tr>';*/
            echo '<tr>';
            echo '<td>План ФРВ</td><td><input name="frv_plan" type="number"  min="0" value="'.$frv_plan.'"></td></>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Факт</td><td><input name="fact" type="number"  min="0" value="'.$fact.'"></td></>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>ФРВ по потребности</td><td><input name="frv_demand" type="number"  min="0" value="'.$frv_demand.'"></td></>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Факт выроботка</td><td><input name="fact_production" type="number" min="0" value="'.$fact_production.'"></td></>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Факт людей в часах</td><td><input name="fact_workers_in_hour" type="text" min="0" value="'.$fact_workers_in_hour.'"></td></>';
            echo '</tr>';
            echo '</table>';
            echo '<button class="btn" name="Save" value="true">';
            echo 'Сохранить';
            echo '</button>';
        }
        echo '</form>';
        echo '<hr>';
        echo '<p>Разработал: Булатов Р.Р.</p>';
    ?>

</div>

</body>


</html>