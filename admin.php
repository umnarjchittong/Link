<!DOCTYPE html>
<html lang="en">

<?php
include("core.php");
$fnc = new App_Object();

if (!$_SESSION["admin"]) {
    header("location:signout.php");
}



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
    <title>FAED's Shortern Link</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php if ($_SESSION["admin"]["fistNameEn"] == "Umnarj") { ?>
        <div class="container-fluid alert alert-info text-center" style="font-size: 0.9rem; font-weight:300">
            <?php print_r($_SESSION["admin"]); ?>
            <hr>
            <?php
            $stat = $fnc->get_link_stat();
            echo "ทั้งหมด " . $stat["all"] . ", ใช้งาน " . $stat["enable"] . ", ยกเลิก " . $stat["delete"];
            ?>
        </div>
    <?php } ?>
    <div class="container-fluid alert alert-warning text-center mb-3" style="font-size: 0.9rem; font-weight:500">
        เวอร์ชั่น <?= $fnc->system_version; ?> (อยากให้ปรับเปลี่ยนอย่างไร กดปุ่ม สอบถาม-แนะนำ ได้เลยครับ)
    </div>

    <div class="container col-12 col-md-12 col-lg-8 mt-3">
        <div class="row">
            <div class="col mt-2">
                <h1 class="" style="color: var(--bs-primary);">FAED's Shortern Link</h1>
            </div>
            <div class="col">
                <div class="float-end">
                    <a href="https://m.me/umnarj" target="_blank" class="col m-2 btn btn-sm btn-primary text-white">สอบถาม-แนะนำ</a>
                    <a href="https://www.the-qrcode-generator.com/" target="_blank" class="col m-2 btn btn-sm btn-success text-white">สร้าง QR</a>
                    <a href="signout.php" target="_top" class="col m-2 btn btn-secondary">Sign-Out</a>
                </div>
            </div>
        </div>
        <hr class="mb-4">

        <?php
        if (isset($_GET["a"]) && $_GET["a"] == "view" && isset($_GET["c"])) {
            // echo '<div class="col-10 col-md-8 mx-auto text-center">';
            // $fnc->gen_alert('<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&choe=UTF-8&chl=' . $fnc->url_hosting . $_GET["c"] . '" title="qr code generator" />', 'QR Code', 'info');
        ?>
            <div class="container col-12 col-md-10 mx-auto mt-5">
                <!-- <div class="alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert"> -->
                <div class="alert alert-danger alert-dismissible fade show p-0" role="alert">
                    <div class="card text-white bg-secondary">
                        <div class="card-header">
                            <span>QR Code : </span>
                            <span style="font-size: 0.9em;"><a href="<?php echo 'https://' . $fnc->url_hosting . $_GET["c"] ?>" target="_blank" class="link-warning"><?php echo $fnc->url_hosting . $_GET["c"] ?></a></span>
                            <button type="button" class="btn-close float-end pb-1  link-warning" data-bs-dismiss="alert" aria-label="Close"></button>
                            <!-- <a href="#close" class="btn-close float-end pb-1 link-warning" data-bs-dismiss="alert" aria-label="Close">x</a> -->
                        </div>
                        <div class="card-body text-center">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&choe=UTF-8&chl=<?= $fnc->url_hosting . $_GET["c"] ?>" title="qr code generator" class="mx-auto" />
                        </div>
                        <div class="card-footer text-end" style="font-size: 0.8em; font-weight:300;">
                            * คลิกขวาเซฟเป็นรูปภาพได้เลยครับ
                        </div>
                    </div>
                </div>
                <div id="qr footer" class="mt-3 mb-5"></div>
            </div>
            
            <!-- </div> -->
        <?php
            // echo '</div>';
        }
        ?>
        <?php
        if (isset($_GET["a"]) && $_GET["a"] == "edit" && isset($_GET["c"]) && $_SESSION["admin"]) {
            $data = $fnc->fread_search($_GET["c"]);
            // print_r($data);
        ?>
            <form action="?a=update" method="POST">
                <div class="mb-3">
                    <label for="url" class="form-label">ระบุลิงก์ปลายทาง</label>
                    <input type="url" class="form-control" name="url" id="url" aria-describedby="urlHelp" required value="<?= $data["url"]; ?>">
                    <div id="urlHelp" class="form-text">* ตัวอย่าง: https://arch.mju.ac.th</div>
                </div>
                <input type="hidden" name="fst" value="update">
                <input type="hidden" name="code" value="<?= $_GET["c"] ?>">
                <button type="submit" class="btn btn-info">บันทึก</button>
                <a href="admin.php" target="_top" class="btn btn-warning">ยกเลิก</a>
                <a href="?a=delete&c=<?= $_GET["c"] ?>" target="_top" class="btn btn-danger float-end">ลบ</a>
            </form>
        <?php
        } else if ($_SESSION["admin"]) {
        ?>
            <form action="?a=createnew" method="POST">
                <!-- <div class="mb-3">
                <label for="title" class="form-label">Title/Name</label>
                <input type="text" class="form-control" name="title" id="title" aria-describedby="titleHelp" required value="my-document">
                <div id="titleHelp" class="form-text">* Title or Name of link</div>
            </div> -->
                <div class="mb-3">
                    <label for="url" class="form-label">ระบุลิงก์ปลายทาง</label>
                    <input type="url" class="form-control" name="url" id="url" aria-describedby="urlHelp" required value="">
                    <div id="urlHelp" class="form-text">* ตัวอย่าง: https://arch.mju.ac.th</div>
                </div>
                <input type="hidden" name="fst" value="createnew">
                <button type="submit" class="btn btn-primary">สร้างลิงก์</button>
            </form>
        <?php
        } else {
            echo '<a href="sign.php" target="_top" class="btn btn-danger">! Please Sign First</a>';
        }
        ?>

        <?php
        if (isset($_GET["a"]) && $_GET["a"] == "update" && $_POST["fst"] == "update" && $_POST["url"]) {
            $fnc->fwrite_update($_POST["code"], "url", $_POST["url"]);
            echo '<meta http-equiv="refresh" content="0.1;url=admin.php">';
        }

        if (isset($_GET["a"]) && $_GET["a"] == "createnew" && $_POST["fst"] == "createnew") {

            // echo "action create new" . "<br>";
            $code = $fnc->gen_code();
            // echo "gen code : " . $code . "<br>";
            // echo "user : " . $_SESSION["admin"]["fistNameEn"] . "<br>";
            $data_form = array("code" => $code, "title" => "คณะสถาปัตย์ฯ", "url" => $_POST["url"], "user" => $_SESSION["admin"]["fistNameEn"], "time" => time(), "status" => "enable");
            // print_r($data_form);
            // echo "your link : " . $fnc->url_hosting . $code . "<br>";

            $str_data = $fnc->fread_data();
            if (!is_null($str_data)) {
                $data_array = array();
                // echo '<hr id=55>text file read data<br>' . $str_data . '<br>';
                $data = json_decode($str_data, true, JSON_UNESCAPED_UNICODE);
                // echo '<hr id=21>array data<br>';
                // print_r($data);
                // echo '<br>';
                $data_array = array_merge(array($data_form), $data);
                // echo '<hr id=21>array push<br>';
                // print_r($data_array);
                // echo '<br>';
            } else {
                // echo '<hr id=25>Text file is Nothing.<br>' . $str_data . '<br>';
                $data_array = array($data_form);
                // echo 'array Set<br>';
                // print_r($data_array);
                // echo '<br>';
            }

            $data = json_encode($data_array);
            // echo "<br>json encode: " . $data;

            $fnc->fwrite_data($data);
            $_SESSION["link_info"] = NULL;
            echo '<div id="countdown" class="text-info text-right float-end"></div>';
            echo '<script>
            var timeleft = 3;
            var downloadTimer = setInterval(function(){
                if(timeleft <= 0){
                    clearInterval(downloadTimer);
                    // document.getElementById("countdown").innerHTML = "Finished";
                } else {
                    document.getElementById("countdown").innerHTML = "Create a link completed. Please wait " + timeleft + " seconds remaining";
                }
                timeleft -= 1;
            }, 1000);
            </script>';
            echo '<meta http-equiv="refresh" content="4;url=admin.php?a=fread">';
        }

        if (isset($_GET["a"]) && $_GET["a"] == "fwrite" && isset($_GET["v"])) {
            $str_data = $fnc->fread_data();
            if (!is_null($str_data)) {
                $data_array = array();
                echo '<hr id=55>text file read data<br>' . $str_data . '<br>';
                $data = json_decode($str_data, true, JSON_UNESCAPED_UNICODE);
                echo '<hr id=21>array data<br>';
                print_r($data);
                echo '<br>';
                $data_array = array_merge(array($_SESSION["link_info"]), $data);
                echo '<hr id=21>array push<br>';
                print_r($data_array);
                echo '<br>';
            } else {
                echo '<hr id=25>Text file is Nothing.<br>' . $str_data . '<br>';
                $data_array = array($_SESSION["link_info"]);
                echo 'array Set<br>';
                print_r($data_array);
                echo '<br>';
            }

            $data = json_encode($data_array);
            echo "<br>json encode: " . $data;

            $fnc->fwrite_data($data);
            $_SESSION["link_info"] = NULL;
            echo '<meta http-equiv="refresh" content="2;url=admin.php?a=fread">';
        }

        if (isset($_GET["a"]) && $_GET["a"] == "new") {
            // $_SESSION["link_info"] = NULL;
            // $data_array = array("code" => $fnc->gen_code(), "title" => "Link1", "url" => "url 1 togo...", "user" => "umnarj", "time" => time(), "status" => "enable");
            // echo 'link info Set<br>';
            // print_r($data_array);
            // echo '<br>';
            // $_SESSION["link_info"] = $data_array;
        }

        // if (isset($_GET["a"]) && $_GET["a"] == "fread") {
        $data = json_decode($fnc->fread_data(), true, JSON_UNESCAPED_UNICODE);
        if (is_array($data)) {
            // print_r($data);
            $i = 0;
            echo '<hr class="mt-4 float-none">';
            echo '<div class="mb-2 col-12 col-md-10 mx-auto">';
            echo '<h4 class="mb-3">ลิงก์ของฉัน...</h4>';
            echo '<ol class="list-group list-group-numbered">';
            foreach ($data as $d) {
                // echo $d["citizenId"] . ' == ' . $_SESSION["admin"]["citizenId"] . '<br>';                    
                if ($d["status"] == "enable" && $d["user"] == $_SESSION["admin"]["fistNameEn"]) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                    // $i++;
                    // echo $i . '. ';
                    echo '<span class="ms-2 me-auto"><a href="' . $d["url"] . '" target="_blank" class="link-primary">' . $fnc->url_hosting . $d["code"] . '</a></span>';
                    // echo ' [by ' . $d["user"];
                    // echo '<span class="float-end"><a href="?a=delete&c=' . $d["code"] . '" target="_top" class="btn btn-danger">DEL</a></span>';
                    echo '<span class="float-end"><a href="?a=edit&c=' . $d["code"] . '" target="_top" class="btn btn-warning">แก้ไข</a></span>';
                    echo '<a href="?a=view&c=' . $d["code"] . '" class="btn btn-info btn_qr">QR</a>';
                    // echo '<a href="?" data-link_code="' . $d["code"] . '" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-info btn_qr">QR</a>';
                    // echo '<span class="me-3 float-end" style="font-size: 0.8em;">[' . date("Y-m-d H:i:s น.", $d["time"]) . ']</span>';
                    echo '</li>';
                } else {
                    // echo '<a href="' . $d["url"] . '" target="_blank" class="link-primary">' . $d["code"] . ' -' . $d["title"] . '</a>';
                    // echo ' by [' . $d["user"] . ' date:' . date("Y-m-d H:i:s น.", $d["time"]) . '] ';
                    // echo ' <span class="text-danger">DELETED</span>';
                    // echo '<br>';
                }
            }
            echo '</ol>';
            echo '</div>';
            $_SESSION["link_info"] = $data;
            echo '<br>';
        } else {
            // echo "not data founded";
        }
        // }

        if (isset($_GET["a"]) && $_GET["a"] == "delete" && isset($_GET["c"])) {
            // $data_array = array();
            // $data = json_decode($fnc->fread_data(), true, JSON_UNESCAPED_UNICODE);
            // if (is_array($data)) {
            //     foreach ($data as $d) {
            //         if ($_GET["c"] == $d["code"]) {
            //             $d["status"] = "delete";
            //         } else {
            //         }
            //         array_push($data_array, $d);
            //     }
            // }
            // // echo "data new : <br>";
            // // print_r($data_array);
            // $data = json_encode($data_array);
            // // echo "<br>json encode: " . $data;

            // $fnc->fwrite_data($data);
            $fnc->fwrite_update($_GET["c"], "status", "delete");
            // $_SESSION["link_info"] = NULL;
            echo '<meta http-equiv="refresh" content="3;url=admin.php">';
        }


        ?>
        <!-- <hr class="mt-3 float-none">
        <div class="row">
            <a href="admin.php?a=new" target="_top" class="col m-2 btn btn-warning">Gen New</a>
            <a href="admin.php?a=fwrite&v=link_info" target="_top" class="col m-2 btn btn-primary">File Write</a>
            <a href="admin.php?a=fread" target="_top" class="col m-2 btn btn-info">My Links</a>
        </div>
        <div class="row">
            <a href="signout.php" target="_top" class="col m-2 btn btn-secondary">Sign-Out</a>
        </div> -->
    </div>
    <div class="float-none my-5"> </div>

    <footer class="text-center text-white fixed-bottom py-1" style="background-color: #5e2809; font-size: 0.8rem;">
        <!-- Grid container -->
        <div class="container m-2"></div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center">
            © 2021 Copyright Faculty of Architecture and Environmental Design, Maejo University<br>
        </div>
        <div class="text-center" style="font-weight:300;">
            Deverloper : umnarj@mju.ac.th | version: <?= $fnc->system_version; ?>
        </div>
        <!-- Copyright -->
    </footer>


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&choe=UTF-8&chl=https://devbanban.com/" title="qr code" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>

</body>

</html>