<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            width: 100vw;
            overflow-x: hidden;
        }

        div.container {
            padding: 20px 0;
        }

        h1 {
            text-align: center;
        }

        div.col {
            padding: 80px;
        }

        div.result {
            margin: 50px 0;
            padding: 20px 0 0 0;
            border-bottom: 3px solid green;
            width: 50%;
        }

        div.column-header {
            display: flex;
            justify-content: space-between;
        }

        div.column-header>a {
            font-size: 15px;
            line-height: 30px;
            min-width: 80px
        }

        div.input-group>span {
            min-width: 200px
        }

        div.input-group>input {
            max-width: 200px
        }

        form.edit {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            padding-bottom: 50px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Fee Calculator</h1>

        <?php
        switch ($method) {
            case 'getCalculator':
                require_once realpath(__DIR__) . '/calculator.php';
                break;
            case 'edit':
                require_once realpath(__DIR__) . '/edit.php';
                break;
            default:
                require_once('./calculator.php');
                break;
        }
        ?>
    </div>

</body>

</html>