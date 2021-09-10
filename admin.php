<!DOCTYPE html>
<html lang="en">

<?php
include("core.php");
$fnc = new App_Object();

if (!$_SESSION["admin"]) {
    header("location:signout.php");
}

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAED's Shortern Link</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php
    if ($_SESSION["admin"]["fistNameEn"] == "Umnarj") {
        $fnc->debug_console("admin : ", $_SESSION["admin"]);
        $fnc->debug_console("stat : ", $fnc->get_link_stat());
    }
    ?>
    <div class="container-fluid alert alert-warning text-center mb-3" style="font-size: 0.9rem; font-weight:500">
        เวอร์ชั่น <?= $fnc->system_version; ?> (เป็นเวอร์ชันที่อยู่ในระหว่างการพัฒนา สามารถแนะนำเพิ่มเติมได้เลยครับ)
    </div>

    <div class="container col-12 col-md-12 col-lg-8 mt-3">
        <div class="row">
            <div class="col mt-2">
                <h3 class="mt-1" style="color: var(--bs-primary);">FAED's Shortern Link</h3>
            </div>
            <div class="col">
                <div class="float-end">
                    <div>
                        <!-- <a href="https://www.the-qrcode-generator.com/" target="_blank" class="col m-2 btn btn-sm btn-success text-white">สร้าง QR</a> -->
                    </div>
                    <div class="float-end">
                        <a href="https://m.me/umnarj" target="_blank" class="col m-2 btn btn-sm btn-primary text-white">สอบถาม-แนะนำ</a>
                        <a href="signout.php" target="_top" class="col m-2 btn btn-secondary">Sign-Out</a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mb-4">

        <?php
        if (isset($_GET["a"]) && $_GET["a"] == "view" && isset($_GET["c"])) {
            $data = $fnc->get_db_col("SELECT links_url FROM links WHERE links_code = '" . $_GET["c"] . "'");
        ?>
            <div class="container col-12 col-md-10 mx-auto mt-5">
                <div class="alert alert alert-dismissible fade show p-0" role="alert">
                    <?php
                    if (isset($_GET["id"])) {
                        // * url dupplicated warning
                        // echo "url dupplicated";
                    ?>
                        <div class="alert alert-info p-4 text-center mb-4 alert-dismissible " role="alert">
                            <?= $data; ?>
                            <br>ลิงก์นี้ท่านเคยสร้างไว้แล้ว
                        </div>
                    <?php
                    }
                    ?>
                    <div class="card text-white bg-secondary">
                        <div class="card-header">
                            <span>QR Code for : </span>
                            <span style="font-size: 0.9em;"><a href="<?php echo 'https://' . $fnc->url_hosting . $_GET["c"] ?>" target="_blank" class="link-warning"><?php echo $fnc->url_hosting . $_GET["c"] ?></a></span>
                            <button type="button" class="btn-close float-end pb-1  link-warning" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="card-body text-center">
                            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&choe=UTF-8&chl=<?= 'https://' . $fnc->url_hosting . $_GET["c"] ?>" title="qr code generator" class="mx-auto" />
                        </div>
                        <div class="card-footer text-end" style="font-size: 0.8em; font-weight:300;">
                            * คลิกขวาเซฟเป็นรูปภาพได้เลยครับ
                        </div>
                        <?php
                        if ($_SESSION["admin"]["fistNameEn"] == "Umnarj") {
                            $fnc->debug_console("destination : ", $data);
                        }
                        ?>
                    </div>
                </div>
                <div id="qr footer" class="mt-3 mb-5"></div>
            </div>
        <?php
        }
        ?>
        <?php
        if (isset($_GET["a"]) && $_GET["a"] == "edit" && isset($_GET["c"]) && $_SESSION["admin"]) {
            $data = $fnc->get_db_array("SELECT links_url FROM links WHERE links_code = '" . $_GET["c"] . "'")[0];
        ?>
            <form action="?a=update" method="POST">
                <div class="mb-3">
                    <label for="url" class="form-label">ระบุลิงก์ปลายทาง</label>
                    <input type="url" class="form-control" name="url" id="url" aria-describedby="urlHelp" required value="<?= $data["links_url"]; ?>">
                    <div id="urlHelp" class="form-text">* ตัวอย่าง: https://arch.mju.ac.th</div>
                </div>
                <input type="hidden" name="fst" value="update">
                <input type="hidden" name="code" value="<?= $_GET["c"] ?>">
                <button type="submit" class="btn btn-info">บันทึก</button>
                <a href="admin.php" target="_top" class="btn btn-warning">ยกเลิก</a>
                <a href="?a=delete&c=<?= $_GET["c"] ?>" target="_top" class="btn btn-danger float-end">ลบ</a>
            </form>
        <?php
        } else if (isset($_GET["a"]) && $_GET["a"] == "dupplicate" && isset($_GET["id"]) && $_SESSION["admin"]) {
        ?>
            <form action="?a=createnew" method="GET">
                <div class="mb-3">
                    <label for="url" class="form-label">ลิงก์ปลายทาง</label>
                    <input type="url" class="form-control" name="url" id="url" aria-describedby="urlHelp" required readonly value="<?= '' ?>">
                    <div id="urlHelp" class="form-text">* ตัวอย่าง: https://arch.mju.ac.th</div>
                </div>
                <input type="hidden" name="fst" value="createnew">
                <!-- <button type="submit" class="btn btn-primary">สร้างลิงก์</button> -->
                <a href="" target="_top" class="btn btn-info">แสดงลิงก์</a>
            </form>
        <?php
        } else if (isset($_GET["a"]) && $_GET["a"] == "delete" && isset($_GET["id"]) && $_SESSION["admin"]) {
        ?>
            <form action="?a=enable" method="POST">
                <div class="mb-3">
                    <label for="url" class="form-label">ลิงก์ปลายทาง</label>
                    <input type="url" class="form-control" name="url" id="url" aria-describedby="urlHelp" required readonly value="<?= '' ?>">
                    <div id="urlHelp" class="form-text">* ตัวอย่าง: https://arch.mju.ac.th</div>
                </div>
                <input type="hidden" name="fst" value="enable">
                <button type="submit" class="btn btn-primary">เปิดใช้งาน</button>
                <a href="admin.php" target="_top" class="btn btn-warning">ยกเลิก</a>
            </form>
        <?php
        } else if ($_SESSION["admin"]) {
        ?>
            <form action="?a=createnew" method="POST">
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
            $sql = "UPDATE links SET links_url='" . $_POST["url"] . "',links_time=CURRENT_TIMESTAMP WHERE links_code = '" . $_POST["code"] . "'";
            $fnc->sql_execute($sql);
            echo '<meta http-equiv="refresh" content="0.1;url=admin.php">';
        }

        if (isset($_GET["a"]) && $_GET["a"] == "createnew" && $_POST["fst"] == "createnew") {
            // * check duplicated link for this user
            $sql = "SELECT links_id, links_code, links_status FROM links WHERE links_url = '" . $_POST["url"] . "' AND links_status = 'enbale'";
            $exist = $fnc->get_db_array($sql)[0];
            if (is_array($exist)) {
                // dupplicated                
                header("location:admin.php?a=view&c=" . $exist["links_code"] . "&id=" . $exist["links_id"]);
                die();
            }

            // * gen code and insert to database
            $code = $fnc->gen_code();
            /*echo "action create new" . "<br>";            
            echo "gen code : " . $code . "<br>";
            echo "user : " . $_SESSION["admin"]["fistNameEn"] . "<br>";
            $data_form = array("code" => $code, "title" => "คณะสถาปัตย์ฯ", "url" => $_POST["url"], "user" => $_SESSION["admin"]["fistNameEn"], "user_id" => $_SESSION["admin"]["citizenId"], "time" => time(), "status" => "enable");
            print_r($data_form);
            echo "your link : " . $fnc->url_hosting . $code . "<br>";*/

            $sql = "INSERT INTO links (links_code, links_title, links_url, links_user, links_user_id) VALUES ('" . $code . "', 'คณะสถาปัตย์ฯ', '" . $_POST["url"] . "', '" . $_SESSION["admin"]["fistNameEn"] . "', '" . $_SESSION["admin"]["citizenId"] . "')";
            $fnc->debug_console("insert sql: " . $sql);
            $fnc->sql_execute($sql);

            // * clear session and countdown 4 seconds to redirect
            $_SESSION["link_info"] = NULL;
            echo '<div id="countdown" class="text-info text-right float-end"></div>';
            echo '<script>
            var timeleft = 3;
            var downloadTimer = setInterval(function(){
                if(timeleft <= 0){
                    clearInterval(downloadTimer);
                    // document.getElementById("countdown").innerHTML = "Finished";
                } else {
                    document.getElementById("countdown").innerHTML = "Create a link completed. Please wait " + timeleft + " seconds remaining.";
                }
                timeleft -= 1;
            }, 1000);
            </script>';
            echo '<meta http-equiv="refresh" content="4;url=admin.php?a=fread">';
        }


        // * get data from database
        $sql = "SELECT links.* FROM links WHERE links.links_user_id = '" . $_SESSION["admin"]["citizenId"] . "' ORDER BY links_time Desc";
        $data = $fnc->get_db_array($sql);
        if ($_SESSION["admin"]["fistNameEn"] == "Umnarj") {
            $fnc->debug_console("my links... ", $data);
        }
        if (is_array($data)) {
            $i = 0;
            echo '<hr class="mt-4 float-none">';
            echo '<div class="mb-2 col-12 col-md-10 mx-auto">';
            echo '<h4 class="mb-3">ลิงก์ของ ' . $_SESSION["admin"]["fistNameEn"] . '</h4>';
            echo '<ol class="list-group list-group-numbered">';
            foreach ($data as $d) {
                if ($d["links_status"] == "enable" && $d["links_user_id"] == $_SESSION["admin"]["citizenId"]) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                    echo '<span class="ms-2 me-auto"><a href="' . $d["links_url"] . '" target="_blank" class="link-primary">' . $fnc->url_hosting . $d["links_code"] . '</a>';
                    // * if count > 0 show badge
                    $visited = $fnc->get_db_col("SELECT COUNT(logs_id) as count_logs FROM logs WHERE links_code = '" . $d["links_code"] . "'");
                    if ($visited > 0) {
                        echo '<span class="badge rounded-pill bg-primary ms-1" style="font-weight:300;">ถูกใช้งาน <strong>' . $visited . '</strong> ครั้ง</span>';
                    }
                    echo '</span>';
                    echo '<span class="float-end"><a href="?a=edit&c=' . $d["links_code"] . '" target="_top" class="btn btn-warning me-2">แก้ไข</a></span>';
                    echo '<a href="?a=view&c=' . $d["links_code"] . '" class="btn btn-info btn_qr">QR</a>';
                    echo '</li>';
                } else {
                }
            }
            echo '</ol>';
            echo '</div>';
            echo '<br>';
        } else {
            // echo "not data founded";
        }

        if (isset($_GET["a"]) && $_GET["a"] == "delete" && isset($_GET["c"])) {
            $sql = "UPDATE links SET links_status='delete',links_time=CURRENT_TIMESTAMP WHERE links_code = '" . $_GET["c"] . "'";
            $fnc->sql_execute($sql);
            echo '<meta http-equiv="refresh" content="0.1;url=admin.php">';
        }

        ?>

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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>

</body>

</html>