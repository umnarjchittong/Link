<?php
session_start();
$_SESSION['coding_indent'] = 0;

ini_set('display_errors', 1);
date_default_timezone_set('Asia/Bangkok');

class Constants
{
    public $data_filename = 'link_data.txt';
    public $google_analytic_id = 'G-62GTDQF33N';
    public $url_hosting = 'faed.mju.ac.th/dev/link/?l=';
    public $system_name = "FAED's Shortern Link";
    public $system_org = "Arch@Maejo University";
    public $system_version = '1.5';
}

class CommonFnc extends Constants
{
    public function debug_console($val1, $val2 = null)
    {
        if (is_array($val1)) {
            // $val1 = implode(',', $val1);
            $val1 = str_replace(
                chr(34),
                '',
                json_encode($val1, JSON_UNESCAPED_UNICODE)
            );
            $val1 = str_replace(chr(58), chr(61), $val1);
            $val1 = str_replace(chr(44), ', ', $val1);
            $val1 = 'Array:' . $val1;
        }
        if (is_array($val2)) {
            // $val2 = implode(',', $val2);
            $val2 = str_replace(
                chr(34),
                '',
                json_encode($val2, JSON_UNESCAPED_UNICODE)
            );
            $val2 = str_replace(chr(58), chr(61), $val2);
            $val2 = str_replace(chr(44), ', ', $val2);
            $val2 = 'Array:' . $val2;
        }
        if (isset($val1) && isset($val2) && !is_null($val2)) {
            echo '<script>console.log("' .
                $val1 .
                '\\n' .
                $val2 .
                '");</script>';
        } else {
            echo '<script>console.log("' . $val1 . '");</script>';
        }
    }

    public function get_client_info()
    {
        $data = array();
        foreach ($_SERVER as $key => $value) {
            // $data .= '$_SERVER["' . $key . '"] = ' . $value . '<br />';
            array_push($data, '$_SERVER["' . $key . '"] = ' . $value);
        }
        return $data;
    }

    public function get_page_info($parameter = null)
    {
        if (!$parameter) {
            $indicesServer = [
                'PHP_SELF',
                'argv',
                'argc',
                'GATEWAY_INTERFACE',
                'SERVER_ADDR',
                'SERVER_NAME',
                'SERVER_SOFTWARE',
                'SERVER_PROTOCOL',
                'REQUEST_METHOD',
                'REQUEST_TIME',
                'REQUEST_TIME_FLOAT',
                'QUERY_STRING',
                'DOCUMENT_ROOT',
                'HTTP_ACCEPT',
                'HTTP_ACCEPT_CHARSET',
                'HTTP_ACCEPT_ENCODING',
                'HTTP_ACCEPT_LANGUAGE',
                'HTTP_CONNECTION',
                'HTTP_HOST',
                'HTTP_REFERER',
                'HTTP_USER_AGENT',
                'HTTPS',
                'REMOTE_ADDR',
                'REMOTE_HOST',
                'REMOTE_PORT',
                'REMOTE_USER',
                'REDIRECT_REMOTE_USER',
                'SCRIPT_FILENAME',
                'SERVER_ADMIN',
                'SERVER_PORT',
                'SERVER_SIGNATURE',
                'PATH_TRANSLATED',
                'SCRIPT_NAME',
                'REQUEST_URI',
                'PHP_AUTH_DIGEST',
                'PHP_AUTH_USER',
                'PHP_AUTH_PW',
                'AUTH_TYPE',
                'PATH_INFO',
                'ORIG_PATH_INFO',
            ];

            // $data = '<table cellpadding="10">';
            $val = "page info : \\n";
            foreach ($indicesServer as $arg) {
                if (isset($_SERVER[$arg])) {
                    // $data .= '<tr><td>' .
                    //     $arg .
                    //     '</td><td>' .
                    //     $_SERVER[$arg] .
                    //     '</td></tr>';
                    // $this->debug_console($arg . " = " . $_SERVER[$arg]);
                    $val .= $arg . ' = ' . $_SERVER[$arg] . "\\n";
                } else {
                    // $data .= '<tr><td>' . $arg . '</td><td>-</td></tr>';
                    // $this->debug_console($arg . " = -");
                    $val .= $arg . ' = -' . "\\n";
                }
            }
            // $data .= '</table>';            
            $this->debug_console($val);
            return $val;
        } else {
            switch ($parameter) {
                case 'thisfilename':
                    if (strripos($_SERVER['PHP_SELF'], '/')) {
                        $data = substr(
                            $_SERVER['PHP_SELF'],
                            strripos($_SERVER['PHP_SELF'], '/') + 1
                        );
                    } else {
                        $data = substr(
                            $_SERVER['PHP_SELF'],
                            strripos($_SERVER['PHP_SELF'], '/')
                        );
                    }
                    // $this->debug_console("this file name = " . $data);
                    return $data;
                    break;
                case 'parameter':
                    if (strripos($_SERVER['REQUEST_URI'], '?')) {
                        parse_str(
                            substr(
                                $_SERVER['REQUEST_URI'],
                                strripos($_SERVER['REQUEST_URI'], '?') + 1
                            ),
                            $data
                        );
                    } else {
                        parse_str(substr($_SERVER['REQUEST_URI'], 0), $data);
                    }
                    // print_r($data);
                    return $data;
                    break;
            }
        }
    }

    public function get_url_filename($val = true)
    {
        if ($val === true) {
            $val = $_SERVER['PHP_SELF'];
        }
        if (isset($val)) {
            if (strpos($val, '?')) {
                $val = substr($val, 0, strpos($val, '?'));
            }

            if (stristr($val, '/')) {
                $val = substr($val, strripos($val, '/') + 1);
            } else {
                $val = substr($val, strripos($val, '/'));
            }
            return $val;
        }
    }

    public function get_url_parameter($val = true, $data_array = false)
    {
        if ($val === true) {
            $val = $_SERVER['REQUEST_URI'];
        }
        if (isset($val) && stristr($val, '?')) {
            if (isset($data_array) && $data_array === true) {
                parse_str(substr($val, strpos($val, '?') + 1), $data);
                // print_r($data);
            } else {
                $data = substr($val, strpos($val, '?') + 1);
            }
            return $data;
        }
    }

    public function gen_google_analytics($id = null)
    {
        if (!isset($id) || $id != '') {
            $id = $this->google_analytic_id;
        }
        echo '<!-- Global site tag (gtag.js) - Google Analytics -->';
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' .
            $id .
            '"></script>';
        echo '<script>';
        echo '  window.dataLayer = window.dataLayer || [];';
        echo '  function gtag(){dataLayer.push(arguments);}';
        echo '  gtag("js", new Date());';
        echo '  gtag("config", "' . $id . '");';
        echo '</script>';
    }

    public function gen_alert(
        $alert_sms,
        $alert_title = 'Alert!!',
        $alert_style = 'danger'
    ) {
        // echo '<div class="app-wrapper">';
        echo '<div class="container col-12 mt-3">';
        // echo '<div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">';
        echo '<div class="alert alert-' . $alert_style . ' alert-dismissible fade show" role="alert">';
        echo '<div class="inner">';
        echo '<div class="app-card-body p-3 p-lg-4">';
        echo '<h3 class="mb-3 text-' .
            $alert_style .
            '">' .
            $alert_title .
            '</h3>';
        echo '<div class="row gx-5 gy-3">';
        echo '<div class="col-12">';
        echo '<div class="text-center">' . $alert_sms . '</div>';
        echo '</div>';
        echo '</div>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        // echo '</div>';
        $this->debug_console($alert_sms);
    }
}

class database extends CommonFnc
{
    // protected $mssql_server = '10.1.3.2';
    // protected $mssql_dbname = 'FAED_PR';
    // protected $mssql_usern = 'sa';
    // protected $mssql_passw = 'faedadmin';
    protected $mysql_server = "10.1.3.5:3306";
    protected $mysql_user = "shortern_link";
    protected $mysql_pass = "faedadmin";
    // protected $mysql_name = "faed_ddm";
    protected $mysql_name = "shortern_link";

    public function open_conn()
    {
        // $conn = mysqli_connect('10.1.3.5', 'faed_ddm', 'faedadmin', 'faed_ddm');        
        // $conn = new mysqli($this->mysql_server, $this->mysql_user, $this->mysql_pass, $this->mysql_name);
        //$conn = new mysqli("10.1.3.5:3306","rims","faedamin","research_rims");
        $conn = mysqli_connect($this->mysql_server, $this->mysql_user, $this->mysql_pass, $this->mysql_name);
        if (mysqli_connect_errno()) {
            // die("Failed to connect to MySQL: " . mysqli_connect_error());
            $this->debug_console("MySQL Error!" . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");
        return $conn;
    }

    public function get_result($sql)
    {
        $result = $this->open_conn()->query($sql);
        return $result;
    }

    public function sql_execute($sql)
    {
        //$this->open_conn()->query($sql);
        $conn = $this->open_conn();
        $conn->query($sql);
        return $conn->insert_id;
    }

    public function sql_execute_debug($st = "", $sql)
    {
        if ($st != "") {
            if ($st == "die") {
                $this->debug("die", "SQL: " . $sql);
            } else {
                $this->debug("", "SQL: " . $sql);
            }
        } else {
            //$this->open_conn()->query($sql);
            $conn = $this->open_conn();
            $conn->query($sql);
            return $conn->insert_id;
        }
    }

    public function sql_secure_string($str)
    {
        return mysqli_real_escape_string($this->open_conn(), $str);
    }

    public function get_db_row($sql)
    {
        if (isset($sql)) {
            $result = $this->get_result($sql);
            if ($result->num_rows > 0) {;
                return $result->fetch_assoc();
            }
            return NULL;
        } else {
            die("fnc get_db_col no sql parameter.");
        }
    }

    public function get_db_array($sql)
    {
        if (isset($sql)) {
            $result = $this->get_result($sql);
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return NULL;
        } else {
            die("fnc get_db_col no sql parameter.");
        }
    }

    public function get_dataset_array($sql, $method = "MYSQLI_NUM")
    {
        // * method = MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH
        return $this->open_conn()->query($sql)->fetch_all(MYSQLI_BOTH);
    }

    // public function get_dataset_array($sql)
    // {
    //     $dataset = array();
    //     if (isset($sql)) {
    //         $result = $this->get_result($sql);
    //         if ($result->num_rows > 0) {
    //             while ($row = $result->fetch_array()) {
    //                 array_push($dataset, array($row[0], $row[1]));
    //             }
    //             return $dataset;
    //         }
    //         //return NULL;
    //     } else {
    //         die("fnc get_db_col no sql parameter.");
    //     }
    // }

    public function get_db_col($sql)
    {
        if (isset($sql)) {
            //echo $this->debug("", "fnc get_db_col sql: " . $sql);
            $result = $this->get_result($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_array();
                return $row[0];
            }
            return NULL;
        } else {
            die("fnc get_db_col no sql parameter.");
        }
    }

    public function get_last_id($tbl = "activity", $col = "act_id")
    {
        $sql = "select " . $col . " from " . $tbl;
        $sql .= " order by " . $col . " Desc Limit 1";
        return $this->get_db_col($sql);
    }

    public function gen_fiscal_year_to_db()
    {
        $sql = "SELECT message_id, message_created FROM message WHERE message_fiscal_year is null";
        $result = $this->get_result($sql);
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $sql = "UPDATE message SET message_fiscal_year='" . $this->get_fiscal_year($data["message_created"]) . "' WHERE message_id = " . $data["message_id"];
                $this->sql_execute($sql);
                $this->debug_console("convert fiscal year: " . $sql);
            }
            echo "convert fiscal year is done.";
        }
    }

    public function get_board_info($col_name = "board_short", $val)
    {
        if ($val) {
            if ($col_name == "board_id") {
                $col_name = $col_name . " = " . $val;
            } else {
                $col_name = $col_name . " = '" . $val . "'";
            }
            return $this->get_db_row("select * from board where " . $col_name);
        }
    }

    public function test_add()
    {
        // funtion get fiscal year
        // echo $this->get_fiscal_year(date("Y-m-d"));
        // die();
        $sql = "INSERT INTO message(
            message_username,
            message_useremail,
            message_userphone,
            message_usertype,
            message_category,
            message_title,
            message_memo,
            message_created,
            message_fiscal_year
        )
        VALUES(
            'รมย์ธีรา ชิดทอง',
            'umnarjchittong@gmail.com',
            '0866581883',
            'บุคลากรภายในมหาวิทยาลัย',
            'ด้านบริการวิชาการ',
            'ทดสอบการส่งข้อความสายตรงคณบดี',
            '" . date("h:i:sa") . " ทำการทดสอบส่งข้อความเข้าระบบสายตรงคณบดี 2021 โดยกำหนดจากข้อความอัตโนมัติ ในหัวข้อเกี่ยวกับการให้บริการวิชาการ ของคณะสถาปัตยกรรมศาสตร์และการออกแบบสิ่งแวดล้อม',
            CURRENT_TIMESTAMP,
            '" . $this->get_fiscal_year(date("Y-m-d")) . "'
        )";
        $this->sql_execute($sql);
        $this->gen_notify_email($_SESSION["admin"]["citizenId"]);
    }

    public function menu_message_hide($board)
    {
        $sql = "SELECT count(message_id) FROM message WHERE message_status <> 'new' AND message_assigned = '" . $board . "'";
        if ($this->get_db_col($sql) < 1) {
            echo " d-none";
        }
    }

    public function gen_notify_email_body($dm)
    {
        $body = '<!doctype html>
        <html lang="en">
        
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
        
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
        
            <title>PHPMailer Text Sending</title>
        
        </head>
        
        <body style="font-family: Kanit, sans-serif;">
            <div class="container">
                <h4 class="mb-5">ระบบสายตรงคณบดี คณะสถาปัตย์ฯ มีการแจ้งเตือนใหม่</h4>
                <div><p>' . $dm["message_title"] . '</p></div>
                <div class="mb-1"><img src="https://arch.mju.ac.th/img/mju_logo.jpg" width="100px"></div>
                <div>
                    <p class="" style="font-size: 0.8em;">คณะสถาปัตยกรรมศาสตร์และการออกแบบสิ่งแวดล้อม<br>มหาวิทยาลัยแม่โจ้<br>โทร 053873350</p>
                </div>
        
            </div>
        
        
        
        
            <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script> -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
        </body>
        
        </html>';
        return $body;
    }

    public function gen_notify_email($pid)
    {
        $sql = "SELECT * FROM setting WHERE setting_user_pid = " . $pid;
        $setting = $this->get_db_row($sql);
        $sql = "SELECT * FROM message order by message_id desc limit 1";
        $dm = $this->get_db_row($sql);
        if ($setting["setting_email_noti"]) {
            $_SESSION["data"] = array(
                "receiver_address" => $setting["setting_email"],
                "receiver_name" => $_SESSION["admin"]["firstName"] . " " . $_SESSION["admin"]["lastName"],
                "from_address" => "archmaejo@gmail.com",
                "from_name" => "archmaejo",
                "reply_address" => "archmaejo@gmail.com",
                "reply_name" => "archmaejo",
                "cc_address" => "",
                "cc_name" => "",
                "subject" => "สายตรงคณบดี มีการแจ้งเตือนใหม่",
                // "content" => "ทดสอบการแจ้งเตือนข้อความ จากระบบสายตรงคณบดี <b>ทาง email ด้วย phpmailer</b>",
                "content" => $this->gen_notify_email_body($dm),
                "attachment" => ""
            );
            echo '<script type="text/javascript">window.open("../phpmailer/mail.php?next=../admin/message.php?p=new","_parent");</script>';
        }
    }

    public function get_message_count_fiscal($this_fiscal = NULL)
    {
        if (!$this_fiscal) {
            $this_fiscal = $this->get_fiscal_year();
        }
        $message = array();
        $message[0]["month"] = 0;
        for ($i = 10; $i <= 12; $i++) {
            // echo $i . " , ";

            $message[$i]["month"] = $i;
            $sql = "Select Count(message_created) as message From message Where message_status != '' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["created"] = $this->get_db_col($sql);
            $message[0]["created"] += $message[$i]["created"];
            $sql = "Select Count(message_created) as message From message Where message_status != 'deleted' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["active"] = $this->get_db_col($sql);
            $message[0]["active"] += $message[$i]["active"];
            $sql = "Select Count(message_created) as message From message Where message_status = 'new' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["new"] = $this->get_db_col($sql);
            $message[0]["new"] += $message[$i]["new"];
            $sql = "Select Count(message_created) as message From message Where message_status = 'read' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["read"] = $this->get_db_col($sql);
            $message[0]["read"] += $message[$i]["read"];
            $sql = "Select Count(message_created) as message From message Where message_status = 'completed' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["completed"] = $this->get_db_col($sql);
            $message[0]["completed"] += $message[$i]["completed"];
            $sql = "Select Count(message_created) as message From message Where message_status = 'deleted' AND Month(message_created) = " . $i . " AND message_fiscal_year = '" . $this_fiscal . "'";
            $message[$i]["deleted"] = $this->get_db_col($sql);
            $message[0]["deleted"] += $message[$i]["deleted"];
            if ($i == 12) {
                $i = 0;
            }
            if ($i == 9) {
                break;
            }
        }
        return $message;
    }
}

class files extends database
{
    // * Modes Description
    // r	Open a file for read only. File pointer starts at the beginning of the file
    // w	Open a file for write only. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
    // a	Open a file for write only. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
    // x	Creates a new file for write only. Returns FALSE and an error if file already exists
    // r+	Open a file for read/write. File pointer starts at the beginning of the file
    // w+	Open a file for read/write. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
    // a+	Open a file for read/write. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
    // x+	Creates a new file for read/write. Returns FALSE and an error if file already exists
    // * End Description

    /*public function fread_data($data_file = null)
    {
        die("din't use anymore");
        if (is_null($data_file)) {
            $data_file = $this->data_filename;
        } else if (!stripos($data_file, ".txt")) {
            $data_file .= ".txt";
        }
        $file = fopen($data_file, "a+") or die("Unable to open file!");
        if (filesize($data_file) > 0) {
            $data = fread($file, filesize($data_file));
            fclose($file);
            return $data;
        } else {
            fclose($file);
            return NULL;
        }
    }

    public function fwrite_data($data, $data_file = null)
    {
        if ($data) {
            if (is_null($data_file)) {
                $data_file = $this->data_filename;
            } else if (!stripos($data_file, ".txt")) {
                $data_file .= ".txt";
            }
            $file = fopen($data_file, 'w') or die('Unable to open file!');
            // echo $data;
            fwrite($file, $data);
            fclose($file);
        }
    }

    public function fwrite_append_data($data, $data_file = null)
    {
        if ($data) {
            if (is_null($data_file)) {
                $data_file = $this->data_filename;
            } else if (!stripos($data_file, ".txt")) {
                $data_file .= ".txt";
            }
            $file = fopen($data_file, 'a+') or die('Unable to open file!');
            // echo $data;
            fwrite($file, $data);
            fclose($file);
        }
    }

    public function fread_search($keycode, $data_file = null)
    {
        $data = json_decode($this->fread_data($data_file), true, JSON_UNESCAPED_UNICODE);
        if (is_array($data)) {
            foreach ($data as $d) {
                if ($d["code"] == $keycode) {
                    // echo "found...";
                    return $d;
                }
            }
        }
    }

    public function fwrite_update($keycode, $col_name = "url", $new_value, $data_file = null)
    {        
        if ($keycode && $new_value) {
            $data_array = array();
            if (is_null($data_file)) {
                $data_file = $this->data_filename;
            } else if (!stripos($data_file, ".txt")) {
                $data_file .= ".txt";
            }
            $data = json_decode($this->fread_data($data_file), true, JSON_UNESCAPED_UNICODE);
            if (is_array($data)) {
                foreach ($data as $d) {
                    if ($keycode == $d["code"]) {
                        $d[$col_name] = $new_value;
                    }
                    array_push($data_array, $d);
                }
            }
            $data = json_encode($data_array);
            $file = fopen($data_file, 'w') or die('Unable to open file!');
            // echo $data;
            fwrite($file, $data);
            fclose($file);
        }
    }*/

    public function get_link_stat($data_file = null)
    {
        $data = $this->get_db_array("select * from links");
        $stat = array(
            "all" => $this->get_db_col("SELECT count(links_id) as count_links FROM links"),
            "enable" => $this->get_db_col("SELECT count(links_id) as count_links FROM links WHERE links_status = 'enable'"),
            "delete" => $this->get_db_col("SELECT count(links_id) as count_links FROM links WHERE links_status = 'delete'"),
            "users" => $this->get_db_col("SELECT count(DISTINCT(links_user_id)) as count_links FROM links WHERE links_status = 'enable'")
        );
        return $stat;
    }

    public function gen_code()
    {
        $rnd_dec = array(random_int(0, 15), random_int(0, 15), random_int(0, 15));
        // echo '<br>';
        // print_r($rnd_dec);
        $sum_dec = NULL;
        $rnd_hex = NULL;
        $chk_sum = NULL;
        foreach ($rnd_dec as $dec) {
            $sum_dec += $dec;
            $rnd_hex .= dechex($dec);
        }
        // echo '<br>' . $sum_dec . '<br>';
        // substr($sum_dec,1);
        // echo '<br>' . substr($sum_dec,0,1) . '<br>';
        for ($i = 0; $i < strlen($sum_dec); $i++) {
            // echo '<br>' . substr($sum_dec,$i,1);
            $chk_sum += substr($sum_dec, $i, 1);
        }
        // echo '<br>' . $chk_sum . '<br>';
        // echo '<br>' . strtoupper($rnd_hex) . $chk_sum . "<br>";
        $code = strtoupper($rnd_hex) . $chk_sum;

        // check from array if founded call gen_code function again else return code
        $sql = "SELECT links.links_code FROM links WHERE links.links_status = 'enable';";
        $code_exist = $this->get_db_array($sql);
        if ($code_exist) {
            // $data_array = json_decode($code_exist, true, JSON_UNESCAPED_UNICODE);
            foreach ($code_exist as $d) {
                if (isset($d["links_code"]) && $d["links_code"] == $code) {
                    $this->gen_code();
                } else {
                    return $code;
                }
            }
        } else {
            return $code;
        }


        // return strtoupper($rnd_hex) . $chk_sum;
    }
}

class App_Object extends files
{
}

class MJU_API extends CommonFnc
{

    public function get_api_info($title = "", $api_url, $print_r = false)
    {
        $array_data = $this->GetAPI_array($api_url);
        echo "<h3 style='color:#1f65cf'>API Information: $title</h3>";
        echo "<h4 style='color:#cf1f7a'>#row: " . $this->get_row_count($array_data) . "</br>";
        echo "#column: " . $this->get_col_count($array_data) . "</br>";
        echo "@column name: <br><span style='color:#741fcf; font-size:0.8em'>";
        $this->get_col_name($array_data, true);
        echo "</span></h4><hr>";
        if ($print_r) {
            print_r($array_data);
            echo "<hr>";
        }
    }

    public function get_row_count($array, $print_r = false)
    {
        return count($array);
    }

    public function get_col_count($array, $print_r = false)
    {
        return count($array[0]);
    }

    public function get_col_name($array, $print_r = false)
    {
        if ($print_r) {
            print_r(array_keys($array[0]));
        }
        return array_keys($array[0]);
    }

    public function gen_array_filter($array, $key, $value)
    {
        $result = array();
        foreach ($array as $k => $val) {
            if ($val[$key] == $value) {
                array_push($result, $array[$k]);
            }
        }
        return $result;
    }

    public function get_array_filters($array, $key1, $value1, $key2 = null, $value2 = null, $key3 = null, $value3 = null, $key4 = null, $value4 = null, $key5 = null, $value5 = null)
    {
        $result = $array;
        $this->debug_console("get_array_filter2 started");

        if ($key5 && $value5) {
            $result = $this->gen_array_filter($result, $key5, $value5);
            $this->debug_console("gen_array_filter condition #5 completed");
        }
        if ($key4 && $value4) {
            $result = $this->gen_array_filter($result, $key4, $value4);
            $this->debug_console("gen_array_filter condition #4 completed");
        }
        if ($key3 && $value3) {
            $result = $this->gen_array_filter($result, $key3, $value3);
            $this->debug_console("gen_array_filter condition #3 completed");
        }
        if ($key2 && $value2) {
            $result = $this->gen_array_filter($result, $key2, $value2);
            $this->debug_console("gen_array_filter condition #2 completed");
        }
        if ($key1 && $value1) {
            $result = $this->gen_array_filter($result, $key1, $value1);
            $this->debug_console("gen_array_filter condition #1 completed");
        }

        if (count($result)) {
            $this->debug_console("#row of result : " . count($result));
            return $result;
        } else {
            return null;
        }
    }

    public function get_row($array_data, $num_row, $print_r = false)
    {
        if (isset($array_data) && isset($num_row)) {
            return $array_data[$num_row];
        } else {
            return null;
        }
    }

    public function get_col($array_data, $num_row, $col_name, $print_r = false)
    {
        if (isset($array_data) && isset($num_row) && isset($col_name)) {
            if ($print_r) {
                print_r($array_data[$num_row][$col_name]);
            }
            return $array_data[$num_row][$col_name];
        } else {
            return null;
        }
    }

    public function get_last_id($tbl = "activity", $col = "act_id")
    {
        $sql = "select " . $col . " from " . $tbl;
        $sql .= " order by " . $col . " Desc Limit 1";
        // return $this->get_db_col($sql);
        $database = new $this->database();
        return $database->get_db_col($sql);
    }

    function arraysearch_rownum($key, $value, $array)
    {
        foreach ($array as $k => $val) {
            if ($val[$key] == $value) {
                return $k;
            }
        }
        return null;
    }

    // not Supported for SSL
    /*
    Function GetAPI_array($API_URL) {
        $data = file_get_contents($API_URL); // put the contents of the file into a variable            
        $array_data = json_decode($data, true);

        return $array_data;
    }
    */

    // update for SSL
    function GetAPI_array($API_URL)
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $data = file_get_contents($API_URL, false, stream_context_create($arrContextOptions)); // put the contents of the file into a variable                   
        $array_data = json_decode($data, true);

        return $array_data;
    }

    function GetAPI_object($API_URL)
    {
        $data = file_get_contents($API_URL); // put the contents of the file into a variable    
        $obj_data = json_decode($data); // decode the JSON to obj        
        return $obj_data;
    }
}
