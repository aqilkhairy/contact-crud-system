<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loginbg">

<?php
include 'functions.php';
$db = pdo_connect_mysql();

if(isset($_SESSION["logged_in"])) {
    header("location: index.php");
}

if(isset($_REQUEST['btn_login'])) {
    $userid = strip_tags($_REQUEST["input_userid"]);
    $userpass = strip_tags($_REQUEST["input_userpass"]);

    if(empty($userid)) {
        echo "<span class='msg'>Please enter user ID.</span>";
    } else if(empty($userpass)) {
        echo "<span class='msg'>Please enter user password.</span>";
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE id=:userid");
            $stmt->execute(array(':userid'=>$userid));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0) {  //first layer matches ID
                if($userid == $row["id"]) { //second layer matches ID
                    $inDBpass = $row["password"];
                    if($userpass == $inDBpass) { //password verification
                        $_SESSION["logged_in"] = $row["id"];
                        echo "<script>
                            alert('Successfully logged in.');
                            window.location = 'index.php';
                        </script>";
                    } else {
                        echo "<span class='msg'>Login failed.</span>";
                    }
                } else {
                    echo "<span class='msg'>User ID not found. You look suspicious.</span>";
                }
            } else {
                echo "<span class='msg'>User ID not found.</span>"; 
            }
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

}

?>

<form method="post" class="login">
    <div class="box">
        <table>
            <tr>
                <td class="cell-label"><label>User ID</label></td>
                <td><input type="text" name="input_userid" class="inputbox"></td>
            </tr>
            <tr>
                <td class="cell-label"><label>User Password</label></td>
                <td><input type="password" name="input_userpass"  class="inputbox"></td>
            </tr>
            <tr>
                <td colspan="2" class="cell-btn"><input type="submit" name="btn_login" value="Login" class="btn"></td>
            </tr>
        </table>
    </div>
</form>

</body>
</html>