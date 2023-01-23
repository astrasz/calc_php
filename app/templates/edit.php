<form class="edit" action="/edit" method="POST">
    <?php
    $index = 0;
    foreach ($params['feeStructure'] as $loan => $fee) :
    ?>
        <div class="mb-3 input-group">
            <span for="newFee" class="input-group-text"><?= $index ?>. Loan <?= $loan ?> PLN</span>
            <input type="number" class="form-control" id="newFee" name="<?= $index ?>" placeholder="Fee: <?= $fee ?> PLN">
        </div>
    <?php
        $index++;
    endforeach
    ?>
    <button type="submit" class="btn btn-primary float-end">Submit</button>
</form>