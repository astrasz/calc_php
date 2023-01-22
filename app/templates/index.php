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
    </style>
</head>

<body>

    <div class="container">
        <h1>Fee Calculator</h1>
        <div class="row align-items-start">
            <div class="col table">
                <table class="table">
                    <h2>Fee Structure</h2>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Loan Amount</th>
                            <th scope="col">Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1;
                        foreach ($params['feeStructure'] as $loan => $fee) :
                        ?>
                            <tr>
                                <th scope="row"><?= $index ?></th>
                                <td><?= $loan ?></td>
                                <td><?= $fee ?></td>
                            </tr>
                        <?php
                            $index++;
                        endforeach
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col">
                <h2>Check Fee For A Loan</h2>

                <form action="/new" method="POST">
                    <div class="mb-3">
                        <label for="inputLoan" class="form-label">Proposed Loan</label>
                        <input type="number" min="1000" max="20000" class="form-control" id="inputLoan" aria-describedby="loanHelp" name="loan">
                        <div id="loanHelp" class="form-text">Type amount from 1 000 PLN to 20 000 PLN</div>
                    </div>
                    <button type="submit" class="btn-sm btn-primary">Check</button>
                </form>

                <div class="result">
                    <h4>Result Fee</h4>
                    <h5><?= $params["feeForLoan"] ?></h5>
                </div>
            </div>
        </div>
    </div>

</body>

</html>