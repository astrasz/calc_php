<div class="row align-items-start">
    <div class="col table">
        <div class="column-header">
            <h2>Fee Structure</h2>
            <a href="/edit" class="btn btn-primary" role="button">Edit</a>
        </div>
        <table class="table">
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
                        <td><?= $loan ?> PLN</td>
                        <td><?= $fee ?> PLN</td>
                    </tr>
                <?php
                    $index++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <div class="col">
        <h2>Check Fee For A Loan</h2>

        <form action="/new" method="POST">
            <div class="mb-3">
                <label for="inputLoan" class="form-label">Proposed Loan In PLN</label>
                <input type="number" min="1000" max="20000" class="form-control" id="inputLoan" aria-describedby="loanHelp" name="loan">
                <div id="loanHelp" class="form-text">Type amount from 1 000 PLN to 20 000 PLN</div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Check</button>
        </form>

        <div class="result">
            <h4>Fee For Loan In PLN</h4>
            <h5><?= $params["feeForLoan"] ?></h5>
        </div>
    </div>
</div>