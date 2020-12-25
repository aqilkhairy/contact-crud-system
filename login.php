<html>
<body>
<?php 

if(isset($errorMsg)) {
    echo 'test';
    foreach($errorMsg as $error) {
        ?>
            <strong> <?php echo $error; ?> </strong>
        <?php
    }
}

if(isset($loginMsg)) {
        ?>
            <strong> <?php echo $loginMsg; ?> </strong>
        <?php
}
?>

<form method="post">
    <label>User ID</label>
    <input type="text" name="input_userid">
    <label>User Password</label>
    <input type="password" name="input_userpass">
    <input type="submit" name="btn_login" value="Login">
</form>

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
        echo "Please enter user ID.";
    } else if(empty($userpass)) {
        echo "Please enter user password.";
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
                        echo 'Login failed.';
                    }
                } else {
                    echo "User ID not found. You look suspicious.";
                }
            } else {
                echo "User ID not found."; 
            }
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

}

?>

</body>
</html>