<?php
ini_set('display_errors', 1);

session_start();
$_SESSION['coding_indent'] = 0;

date_default_timezone_set('Asia/Bangkok');

class Constants
{
    public $data_filename = 'link_data.txt';
    public $google_analytic_id = 'G-62GTDQF33N';
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

            echo '<table cellpadding="10">';
            $val = "page info : \\n";
            foreach ($indicesServer as $arg) {
                if (isset($_SERVER[$arg])) {
                    echo '<tr><td>' .
                        $arg .
                        '</td><td>' .
                        $_SERVER[$arg] .
                        '</td></tr>';
                    // $this->debug_console($arg . " = " . $_SERVER[$arg]);
                    $val .= $arg . ' = ' . $_SERVER[$arg] . "\\n";
                } else {
                    echo '<tr><td>' . $arg . '</td><td>-</td></tr>';
                    // $this->debug_console($arg . " = -");
                    $val .= $arg . ' = -' . "\\n";
                }
            }
            echo '</table>';
            $this->debug_console($val);
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
        echo '<div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">';
        echo '<div class="inner">';
        echo '<div class="app-card-body p-3 p-lg-4">';
        echo '<h3 class="mb-3 text-' .
            $alert_style .
            '">' .
            $alert_title .
            '</h3>';
        echo '<div class="row gx-5 gy-3">';
        echo '<div class="col-12">';
        echo '<div>' . $alert_sms . '</div>';
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

class files extends CommonFnc
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

    public function fread_data($data_file = null)
    {
        if (is_null($data_file)) {
            $data_file = $this->data_filename;
        }
        $file = fopen($data_file, "r") or die("Unable to open file!");
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
            }
            $file = fopen($data_file, 'w') or die('Unable to open file!');
            // echo $data;
            fwrite($file, $data);
            fclose($file);
        }
    }

    public function chk_duplicate($sample_data)
    {
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
        $str_data = $this->fread_data();
        if (!is_null($str_data)) {
            $data_array = json_decode($str_data, true, JSON_UNESCAPED_UNICODE);
            foreach ($data_array as $f_data) {
                if ($f_data["code"] == $code) {
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
