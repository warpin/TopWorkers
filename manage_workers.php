<!DOCTYPE html>
<html lang="ru" xmlns:table-layout="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <meta charset="cp-1251">
    <title>����������������� ����������</title>
</head>
<body>
<div class="login-form">
    <h1 align="left"><a href="http://top.prettl.ru/manage_workers.php" class="h1link">����������������� ����������</a></h1>
    <h1 align="left"><a href="http://top.prettl.ru/manage_score.php" class="h1link">���������� ������� ����������</a></h1>

    <form name=form_for_btn method="GET">
        <button name="add_worker" value="true" class="btn" onclick="PopUpShow()">
            �������� ���������
        </button>
    </form>
    <p></p>

    <table align="center">
        <tr>
            <td class="table_font">�</td>
            <td class="table_font">��� ���������</td>
            <td class="table_font" width="5%"></td>
            <td class="table_font" width="5%"></td>
        </tr>
    <?php
    require './config/db_connect.php';
    $conn = new DB_CONNECT();
    $db = new DB_CONNECT();

    $result = mysql_query("SELECT * FROM workers ORDER BY fio");

    if (!empty($result) and mysql_num_rows($result) > 0) {
        // check for empty result
        $count=1;
        while ($row = mysql_fetch_array($result)) {
            echo '<tr>';
            echo('<td><span class="table_loc">'.$count.'</span></td>');
            echo('<td><span class="table_loc">'.$row["fio"].'</span></td>');
            echo('<td><a class="link" href="./manage_workers.php?edit='.$row["id"].'">�������������</a></td>');
            echo('<td><a class="link" href="./manage_workers.php?del='.$row["id"].'">�������</a></td>');
            echo '</tr>';
            $count++;
        }
    } else {
        // no data found
        echo "No data found";
    }
    ?>
    </table>

    <script src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
    <script>
        $(document).ready(function(){
            //������ PopUp ��� �������� ��������
            //PopUpHide();
        });
        //������� ����������� PopUp
        function PopUpShow(){
            $("#popup1").show();
        }
        //������� ������� PopUp
        function PopUpHide(){
            location.href = "http://top.prettl.ru/manage_workers.php";
            $("#popup1").hide();
        }
    </script>


</div>
</body>
</html>

<?php
    if(isset($_GET['worker_name'])){
        if(!empty($_GET['worker_name'])){
            $worker_name=$_GET['worker_name'];
            $result = mysql_query("INSERT INTO workers(fio) VALUES ('$worker_name')");
            if (!$result) {
                //$add_worker_status="failed";
                echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php?add_worker=failed">';

                //header("Location: http://top.prettl.ru/manage_workers.php?add_worker=failed");
            } else {
                echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php?add_worker=success">';
                //echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php?add_worker=true">';
                //$add_worker_status="success";
                //header("Location: http://top.prettl.ru/manage_workers.php?add_worker=success");
            }
        }else{
            echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php?add_worker=failed">';
            //$add_worker_status="failed";
            //header( "Location: http://top.prettl.ru/manage_workers.php?add_worker=failed" );
        }
    }
    if(isset($_GET['add_worker'])){
        echo '<div class="b-popup" id="popup1">';
        echo '<div class="b-container">';
        echo '<h1>���������� ���������</h1>';
        echo '<div class="form-group">';
        echo '<form name=add_worker_form action="manage_workers.php" method="GET">';
        echo '<input type="text" required class="form-control" placeholder="������� �.�." name="worker_name">';
        echo '<button class="btn" type="submit" name="add_worker" value="true">��������</button>';
        echo '<button type="button" class="btn" name="cancel" onclick="PopUpHide()">������</button>';
        echo '</form>';
        echo '</div>';
        if($_GET['add_worker']=="success"){
            echo '<p>������� ���������</p>';
            //echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php?add_worker=true">';
        }
        if($_GET['add_worker']=="failed")echo '<p>������ ����������</p>';
        echo '</div>';
        echo '</div>';
    }

    if(isset($_GET['cancel'])){
        header('Location: http://top.prettl.ru/manage_workers.php');
    }
    if(isset($_GET['del'])){
        $id_to_delete=$_GET['del'];
        if(!empty($_GET['del'])){
            $result = mysql_query("DELETE FROM workers WHERE id='$id_to_delete'");
            if (!$result) {
                echo "<p>������ ��������</p>";
            }
            echo '<META HTTP-EQUIV="refresh" CONTENT="0; http://top.prettl.ru/manage_workers.php">';
        }
    }
?>





