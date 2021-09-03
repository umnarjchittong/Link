<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Site</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <div class="container col-4 p-4">
        <h1>Arch@Maejo </h1>
        <h3>Short URL Generator.</h3>
        <hr class="my-4">
        <?php
        if (isset($_GET["l"])) {
            include("core.php");
            $fnc = new App_Object();

            $data = json_decode($fnc->fread_data(), true, JSON_UNESCAPED_UNICODE);
            if (is_array($data)) {
                foreach ($data as $d) {
                    if ($_GET["l"] == $d["code"] && $d["status"] == "enable") {
                        echo '<meta http-equiv="refresh" content="0;url=' . $d["url"] . '">';
                        break;
                    }
                }
                $_SESSION["link_info"] = $data;
            } else {
                echo '<div class="alert alert-danger h3">!! Wrong Link Code 1.</div>';
            }
            echo '<div class="alert alert-danger h3">!! Wrong Link Code 2.</div>';
        } else {
            echo '<div class="alert alert-danger h3">!! Noting Code.</div>';
        }
        ?>

    </div>


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