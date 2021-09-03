<!DOCTYPE html>
<html lang="en">

<?php

include("core.php");
$fnc = new App_Object();

// $f_data = fopen($data_file, 'r') or die('Unable to open file!'); 
/* ? Modes	Description
r	Open a file for read only. File pointer starts at the beginning of the file
w	Open a file for write only. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
a	Open a file for write only. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
x	Creates a new file for write only. Returns FALSE and an error if file already exists
r+	Open a file for read/write. File pointer starts at the beginning of the file
w+	Open a file for read/write. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
a+	Open a file for read/write. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
x+	Creates a new file for read/write. Returns FALSE and an error if file already exists
*/
// $txt = "John Doe\n";
// fwrite($f_data, $txt);
// $txt = "Jane Doe\n";
// fwrite($f_data, $txt);
// fclose($f_data);
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Site</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <h1>Hello-Bootstrap</h1>
    <a href="link_data.txt" target="_blank" class="link-primary">Link Data (Text File)</a>
    <hr class="my-4">

    <?php


    if (isset($_GET["a"]) && $_GET["a"] == "fwrite" && isset($_GET["c"])) {
        if (count($_COOKIE) > 0) {
            echo "Cookies are enabled.";
        } else {
            echo "Cookies are disabled.";
        }
        if (!isset($_COOKIE["link_info"])) {
            echo "Cookie named '" . "link_info" . "' is not set!";
        } else {
            echo "Cookie '" . "link_info" . "' is set!<br>";
            echo "Value is: " . $_COOKIE["link_info"];
        }
        $fnc->fwrite_data($_COOKIE["link_info"]);
        setcookie("link_info", "", time() - 3600);
    }

    if (isset($_GET["a"]) && $_GET["a"] == "new") {
        $_COOKIE["link_info"] = NULL;
        $str_data = $fnc->fread_data();
        if (!is_null($str_data)) {
            echo '<hr id=55>text file read<br>' . $str_data . '<br>';
            $data_array = json_decode($str_data, true, JSON_UNESCAPED_UNICODE);
            $data_array = array();

            array_push($data_array, array("code" => $fnc->gen_code(), "title" => "Link1", "url" => "url 1 togo...", "user" => "umnarj", "time" => time(), "status" => "enable"));
            echo '<hr id=21>array push<br>';
            print_r($data_array);
            echo '<br>';
        } else {
            echo '<hr id=25>Text file is Nothing.<br>' . $str_data . '<br>';
            $data_array = array(
                array("code" => $fnc->gen_code(), "title" => "Link1", "url" => "url 1 togo...", "user" => "umnarj", "time" => time(), "status" => "enable")
            );
            echo 'array Set<br>';
            print_r($data_array);
            echo '<br>';
        }

        $data = json_encode($data_array);
        setcookie("link_info", $data, time() + (5), "/"); // 5 minute
        $_COOKIE["link_info"] = $data;

        if (!isset($_COOKIE["link_info"])) {
            echo "Cookie named '" . "link_info" . "' is not set!";
        } else {
            echo "Cookie '" . "link_info" . "' is set!<br>";
            echo "Value is: " . $_COOKIE["link_info"];
        }
    }

    // 


    // $data = array(
    //     array("code" => $fnc->gen_code(), "title" => "Link1", "rul" => "url 1 togo...", "user" => "umnarj", "time" => time(), "status" => "enable"),
    //     array("code" => $fnc->gen_code(), "title" => "Link2", "rul" => "url 2 togo...", "user" => "umnarj", "time" => time(), "status" => "enable")
    // );
    // $data = json_encode($data);
    // echo '<hr>' . $data . '<br>';


    // echo $str_data . "<br>NEW<br>";

    // $str_data .= " -TOM-\n";

    // echo $str_data;

    // $data = str_replace('"', "'", $data);

    // echo "<hr>" . $data;
    // $fnc->fwrite_data($data);

    // echo "<hr id='91'>";
    // $fnc->gen_code();

    ?>
    <div class="container">
        <hr class="mt-3">
        <div class="row">
            <a href="index.php?a=new" target="_top" class="col m-2 btn btn-warning">Gen New</a>
            <a href="index.php?a=fwrite&c=link_info" target="_top" class="col m-2 btn btn-primary">File Write</a>
        </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>
</body>

</html>