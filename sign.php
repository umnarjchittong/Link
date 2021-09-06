<?php
header("location:sign_admin.php?p=admin");
die();

ini_set('display_errors', 1);


require_once('core.php');
require_once('plugins/nusoap.php');

// * Setup the initial config
$WebToken = "940297e31f2343d793fb5867913f083d";
$AuthPath = "https://passport.mju.ac.th?W=" . $WebToken;
$SignInSuccess_URL = "https://faed.mju.ac.th/ddm/sign/admin/php";
$SignInFailure_URL = "https://faed.mju.ac.th/ddm/e401.html";

// * Check for request parameter
if (empty($_REQUEST["T"])) {
    // * If no parameter to the sign in form
    echo "<meta http-equiv='refresh' content='0.1; URL=$AuthPath'>";
} else {
    // * If I get a parameter, Set the parameter get the PID    
    $SoapClient = new nusoap_client('https://passport.mju.ac.th/login.asmx?wsdl', true);
    $response = $SoapClient->call('CitizenID', array('WebsiteToken' => $WebToken, 'LoginToken' => $_GET["T"]));
    if ($response["CitizenIDResult"]) {
        $pid = $response["CitizenIDResult"];
    } else {
        echo "<meta http-equiv='refresh' content='0.1; URL=$AuthPath'>";
    }

    if ($pid) {
        // * Using PID get the API information
        // echo "pid: " . $pid;
        $MJU_API = new MJU_API();
        $API_URL = "https://api.mju.ac.th/Person/API/PERSON9486bba19bca462da44dc8ac447dea9723052020/";

        // get boards for database access
        $_SESSION["boards"] = $fnc_db->get_db_array("SELECT * FROM board order by board_id");

        // ? Is API info
        // $MJU_API->get_api_info("SignIn Info", $API_URL . $pid, true);
        $api_array = $MJU_API->GetAPI_array($API_URL . $pid)[0];
        $board = null;
        $adminview = null;
        switch ($api_array["fistNameEn"]) {
            case "Umnarj":
                $auth_lv = 9;
                $adminview = 9;
                break;
            case "Chokanan":
                $auth_lv = 7;
                $board = 1;
                break;
            case "Phongnapa":
                $auth_lv = 3;
                break;
            case "Thamniap":
                $auth_lv = 5;
                $board = 2;
                break;
            case "Nachawit":
                $auth_lv = 5;
                $board = 3;
                break;
            case "Phansak":
                $auth_lv = 5;
                $board = 4;
                break;
            case "Punravee":
                $auth_lv = 5;
                $board = 5;
                break;
            case "Porntip":
                $auth_lv = 5;
                $board = 6;
                break;
            case "Thawatchai":
                $auth_lv = 5;
                $board = 7;
                break;
            default:
                $auth_lv = NULL;
                $board = NULL;
                $adminview = null;
        }

        $_SESSION["admin"] = array(
            "citizenId" => $api_array["citizenId"],
            "titleName" => $api_array["titleName"],
            "titlePosition" => $api_array["titlePosition"],
            "firstName" => $api_array["firstName"],
            "lastName" => $api_array["lastName"],
            "titleNameEn" => $api_array["titleNameEn"],
            "fistNameEn" => $api_array["fistNameEn"],
            "lastNameEn" => $api_array["lastNameEn"],
            "position" => $api_array["position"],
            "adminPositionType" => $api_array["adminPositionType"],
            "e_mail" => $api_array["e_mail"],
            "personnelPhoto" => $api_array["personnelPhoto"],
            "auth_lv" => $auth_lv,
            "board_id" => "",
            "board_short" => "",
            "board" => "",
            "board_fullname" => "",
            "setting_email_notify" => "",
            "setting_line_notify" => "",
            "setting_line_id" => "",
            "setting_fb_notify" => "",
            "setting_fb_id" => "",
            "adminview" => Null
        );

        if (isset($board) && $board > 0) {
            $_SESSION["admin"]["board_id"] = $_SESSION["boards"][$board]["board_id"];
            $_SESSION["admin"]["board_short"] = $_SESSION["boards"][$board]["board_short"];
            $_SESSION["admin"]["board"] = $_SESSION["boards"][$board]["board_name"];
            $_SESSION["admin"]["board_fullname"] = $_SESSION["boards"][$board]["board_fullname"];
        }

        if ($adminview) {
            $_SESSION["admin"]["adminview"] = $adminview;
        }

        // pull setting from database
        $sql = "SELECT * FROM setting WHERE setting_user_pid = '" . $_SESSION["admin"]["citizenId"] . "' AND setting_status = 'enable'";
        $row_setting = $fnc_db->get_db_row($sql);
        if (isset($row_setting["setting_email"])) {
            $_SESSION["admin"]["e_mail"] = $row_setting["setting_email"];
        }
        if (isset($row_setting["setting_email_noti"])) {
            $_SESSION["admin"]["setting_email_notify"] = $row_setting["setting_email_noti"];
        }
        if (isset($row_setting["setting_line"])) {
            $_SESSION["admin"]["setting_line_id"] = $row_setting["setting_line"];
            if (isset($row_setting["setting_line_noti"])) {
                $_SESSION["admin"]["setting_line_notify"] = $row_setting["setting_line_noti"];
            }
        }
        if (isset($row_setting["setting_fb"])) {
            $_SESSION["admin"]["setting_fb_id"] = $row_setting["setting_fb"];
            if (isset($row_setting["setting_fb_noti"])) {
                $_SESSION["admin"]["setting_fb_notify"] = $row_setting["setting_fb_noti"];
            }
        }

        // print_r($_SESSION["admin"]);
        // echo "<hr>";
        // echo $_SESSION["admin"]["auth_lv"];
        // echo "<hr>";
        // if ($_SESSION["admin"]) {
        //     echo "session ok";
        // } else {
        //     echo "no session";
        // }
        // echo "<hr>";
        // echo "<a href='../admin/index.php'>Go Admin</a>";

        if (isset($auth_lv) && $auth_lv > 0) {
            echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/admin/index.php">';
        } else {
            echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/e401.php?err=ท่านไม่มีสิทธิ์ใช้ระบบนี้">';
        }
    } else {        
        echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/e401.php?err=ระบบไม่พบข้อมูลของท่าน โปรดติดต่อฝ่ายไอที umnarj@mju.ac.th">';
    }
}
