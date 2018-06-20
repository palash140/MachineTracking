<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['details']) < 1 || strlen($_POST['priority']) < 1 || strlen($_POST['name']) < 1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: complaint_form.php');
            return;
        }
        else
        {
                $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
                $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row=== FALSE)
                {
                    $_SESSION['error'] = "Invalid MAC ADDRESS";
                    header('Location: complaint_form.php');
                    return;
                }
                $mid = $row['machine_id'];
                
                $_POST['dop']=date('y-m-d',strtotime($_POST['dop']));
                $stmt = $pdo->prepare('INSERT INTO complaint_book (date_of_complaint, machine_id, complaint_details, priority, complaint_by) VALUES (:doc, :mid, :cd, :priority, :complaint_by)');
                    $stmt->execute(array(':doc' => date('y-m-d'), ':mid' => $mid, ':cd' => $_POST['details'], ':priority' => $_POST['priority'], ':complaint_by' => $_POST['name']));
                $_SESSION['success'] = "Machine Added Successfully";
                    header('Location: home.php');
                    return;
            

        }
    }
?>
<html>
<head>
    <title>Machine Tracking</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style5.css">
    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
                   <div class="wrapper">
                <?php include "navbar.php" ;?>

    <div class="container" id="content">
    <div class="page-header">
    <h1>Register Complaint</h1>
    </div>
    <?php
    if ( isset($_SESSION['error']) )
    {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST" action="complaint_form.php">

    <div class="input-group">
    <span class="input-group-addon">MAC ADDRESS </span>
    <input type="text" name="mac_addr" class="form-control"> </div><br/>
    <div class="input-group">
    <span class="input-group-addon">Complaint Details </span>
    <input type="text" name="details" class="form-control"> </div><br/>
    <div class="input-group">
    <span class="input-group-addon">Priority</span>
    <input type="text" name="priority" placeholder="in no. of days" class="form-control"> </div><br/>
    <div class="input-group">
    <span class="input-group-addon">Complaint By </span>
    <input type="text" name="name" class="form-control"> </div><br/>
    

    <input type="submit" value="Register Complaint" class="btn btn-info">
    <input type="submit" name="cancel" value="Cancel" class="btn btn-info">
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>