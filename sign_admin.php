<!DOCTYPE html>
<html lang="en">

<head>
    <title>FAED's Links</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Dean's direct message">
    <meta name="author" content="Umnarj Chittong">
    <link rel="shortcut icon" href="img/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">

</head>

<body>

    <?php
    ini_set('display_errors', 1);

    if (isset($_GET["user"]) && $_GET["user"]) {
        require_once("core.php");        
        $fnc = new App_Object();
        $MJU_API = new MJU_API();
        $API_URL = "https://api.mju.ac.th/Person/API/PERSON9486bba19bca462da44dc8ac447dea9723052020/";
        // ! user_token_key = "065bd5c1-3fe3-44a9-a15c-945c4428188c";

        

        $fnc->debug_console("user: " . $_GET["user"]);

        switch ($_GET["user"]) {
            case "umnarj":
                $API_URL .= "3500700238956";
                $auth_lv = 9;
                break;
            case "salinee":
                $API_URL .= "1509900465605";
                $auth_lv = 3;
                break;
            case "judarad":
                $API_URL .= "3501300406151";
                $auth_lv = 3;
                break;            
        }

        // echo "<hr>" . $API_URL;
        $fnc->debug_console("API URL: " . $API_URL);
        $api_array = $MJU_API->GetAPI_array($API_URL)[0];
        $_SESSION["admin"] = array(
            "citizenId" => $api_array["citizenId"],
            "titleName" => $api_array["titleName"],
            "titlePosition" => $api_array["titlePosition"],
            "firstName" => $api_array["firstName"],
            "lastName" => $api_array["lastName"],
            "titleNameEn" => $api_array["titleNameEn"],
            "fistNameEn" => $api_array["fistNameEn"],
            "lastNameEn" => $api_array["lastNameEn"],
            "e_mail" => $api_array["e_mail"],
            "auth_lv" => $auth_lv
        );

        echo "<hr>";
        print_r($_SESSION["admin"]);


        // header("location:../admin/");

        // echo '<hr><a href="../admin/">NEXT >></a>';
        echo '<hr class="my-3"><a class="btn btn-primary btn-lg text-white" href="admin.php">NEXT >></a></div>';
    } else {
        if (!$_GET["p"] == "admin") {
            die();
        }
        echo "<h1>NO user id</h1>";
        echo '<br><h3><a href="?user=umnarj">UMNARJ >></a></h3>';
        echo '<br><h3><a href="?user=salinee">SALINEE</a></h3>';
        echo '<br><h3><a href="?user=judarad">JUDARAD</a></h3>';
        echo '<hr>';
        // echo '<br><a href="../admin/admin.php">page not found</a>';
        // echo '<br><a href="../img/">forbiden</a>';
    }

    if (isset($_GET["p"]) && !$_GET["p"] == "admin") {
        die();
    }

    if (isset($_GET["heading"]) && $_GET["heading"] == "admin") {
        echo "<br> ready for heading to admin page.";
        // header("location:../admin/");
    }

    // echo '<hr>';
    // echo '<br><a href="../admin/admin.php">page not found</a>';
    // echo '<br><a href="../img/">forbiden</a>';


    ?>

</body>

</html>