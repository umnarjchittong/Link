<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAED's Shortern Link</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <style>
        li {
            padding: 4px 0 4px 0;
        }
    </style>
</head>

<body style="font-size: 1em;">

    <div class="container col-12 col-md-10 p-4">
        <div class="row mb-0">
            <div class="col">
                <h1 class="text-success">FAED's Shortern Link</h1>
                <h3 class="text-mute">by Arch@Maejo </h3>
            </div>
            <div class="col">
                <a href="sign.php" target="_top" class="btn btn-lg btn-primary float-end mt-4">Sign-In</a>
            </div>
        </div>
        <hr class="my-4">
        <?php
        if (isset($_GET["l"])) {
            include("core.php");
            $fnc = new App_Object();

            $data = json_decode($fnc->fread_data(), true, JSON_UNESCAPED_UNICODE);
            if (is_array($data)) {
                print_r($data);
                foreach ($data as $d) {
                    if ($_GET["l"] == $d["code"] && $d["status"] == "enable") {
                        echo '<meta http-equiv="refresh" content="0;url=' . $d["url"] . '">';
                        break;
                    }
                }
                $_SESSION["link_info"] = $data;
            } else {
                echo '<div class="alert alert-danger h3">!! รหัสลิงก์ไม่ถูกต้อง1.</div>';
            }
            echo '<div class="alert alert-danger h3">!! รหัสลิงก์ไม่ถูกต้อง.</div>';
        } else {
            // echo '<meta http-equiv="refresh" content="0; URL=sign.php">';
            // echo '<div class="alert alert-danger h3">!! ไม่พบรหัสลิงก์.</div>';
        ?>
            <div>
                <h4 class="text-primary mt-5">ความสามารถของระบบนี้</h4>
            </div>
            <div>
                <ol>
                    <li>ระบบสามารถย่อลิงก์ยาวๆ ให้สั้นลงเหลือ 22 ตัวอักษร</li>
                    <li>นั่นส่งผลให้เมื่อนำไปสร้างเป็น QR Code จะทำให้สแกนติดง่ายขึ้น รวดเร็วขึ้น</li>
                    <li>หากลิงก์ปลายทางมีการเปลี่ยนแปลง สามารถกลับมาแก้ไข โดยไม่ต้องเสียเวลาสร้าง QR Code ใหม่</li>
                    <li>นอกจากกลับมาแก้ไขได้แล้ว ยังสามารถลบลิงก์ที่ไม่ต้องการใช้แล้วได้อีกด้วย</li>
                    <li>เพื่อความปลอดภัย ผู้ใช้งานแต่ละคน จะเห็นข้อมูลที่ตนเองสร้า่งไว้เท่านั้น</li>
                    <li>บุคลากรในมหาวิทยาลัยแม่โจ้ สามารถ Sign-In เข้าใช้ได้ทุกท่าน</li>
                    <li>หลังจาก Sign-In จะมีลิงก์ สร้าง QR ไว้ให้บริการ</li>
                </ol>
            </div>

            <div>
                <h4 class="text-primary mt-5">ขั้นตอนการใช้งาน</h4>
            </div>
            <div>
                <ol>
                    <li>คลิก <a href="" target="_top">Sign-In</a> เพื่อลงชื่อเข้าสู่ระบบด้วยรหัสผ่านของ <strong>ERP.mju.ac.th</strong></li>
                    <li>สร้าง แค่กำหนดลิงก์ปลายทาง แล้วกดปุ่ม สร้างลิงก์</li>
                    <li>แก้ไข ให้กดปุ่ม แก้ไข แล้วระบุลิงก์ปลายทางใหม่ แล้วกดปุ่ม บันทึก</li>
                    <li>ลบ ให้กดปุ่ม แก้ไข แล้วกดปุ่ม ลบ</li>
                    <li>กดปุ่ม QR เพื่อสร้างลิงค์ในรูปแบบ QR Code</li>
                    <li>ออกจากระบบ กดปุ่ม Sign-Out</li>
                    <li>มีข้อสอบถาม หรือคำแนะนำ กดปุ่ม สอบถาม-แนะนำ ได้ครับ</li>
                </ol>
            </div>

        <?php
        }
        ?>

    </div>

    <footer class="text-center text-white fixed-bottom py-1" style="background-color: #5e2809; font-size: 0.8rem;">
        <!-- Grid container -->
        <div class="container m-2"></div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center">
            © 2021 Copyright Faculty of Architecture and Environmental Design, Maejo University<br>
        </div>
        <div class="text-center" style="font-weight:300;">
            Deverloper : umnarj@mju.ac.th
        </div>
        <!-- Copyright -->
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        function myFunction() {
            /* Get the text field */
            var copyText = document.getElementById("myInput");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }
    </script>
</body>

</html>