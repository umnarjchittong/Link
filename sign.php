<?php
// header("location:sign_admin.php?p=admin");
// die();

ini_set('display_errors', 1);


require_once('core.php');
require_once('plugins/nusoap.php');

// * Setup the initial config
$WebToken = "940297e31f2343d793fb5867913f083d";
$AuthPath = "https://passport.mju.ac.th?W=" . $WebToken;
$SignInSuccess_URL = "admin.php";
$SignInFailure_URL = "signout.php";

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
        // $_SESSION["boards"] = $fnc_db->get_db_array("SELECT * FROM board order by board_id");

        // ? Is API info
        // $MJU_API->get_api_info("SignIn Info", $API_URL . $pid, true);
        $api_array = $MJU_API->GetAPI_array($API_URL . $pid)[0];
        $board = null;
        $adminview = null;
        switch ($api_array["fistNameEn"]) {
            case "Umnarj":
                $auth_lv = 9;
                break;
            default:
                $auth_lv = 3;
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
            "auth_lv" => $auth_lv
        );
        

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
            echo "you have authentication level";
            // echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/admin/index.php">';
        } else {
            echo "you have no authorize";
            // echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/e401.php?err=ท่านไม่มีสิทธิ์ใช้ระบบนี้">';
        }
    } else {        
        echo "your info is not founded";
        // echo '<meta http-equiv="refresh" content="0;url=https://faed.mju.ac.th/ddm/e401.php?err=ระบบไม่พบข้อมูลของท่าน โปรดติดต่อฝ่ายไอที umnarj@mju.ac.th">';
    }
}
