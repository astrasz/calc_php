<?php

declare(strict_types=1);

namespace App\Controller;

use App\View;
use App\Controller\AbstractController;
use App\Model\LoanProposal;
use App\Service\Calculator\FeeCalculator;
use App\Service\FeeStructure;
use App\Utils\Debug;
use Exception;

class Calculator extends AbstractController
{

    use Debug;

    public function __construct(
        private View $view = new View(),
        private FeeStructure $feeStructureService = new FeeStructure(),
        private FeeCalculator $feeCalculatorService = new FeeCalculator(),
    ) {
        parent::__construct();
    }


    public function getCalculator(): void
    {

        $feeForLoan = isset($this->get['feeForLoan']) ? $this->get['feeForLoan'] : 0;

        try {
            $feeStructure = $this->feeStructureService->getFeeStructure();
            $data = [
                'feeStructure' => $feeStructure,
                'feeForLoan' => $feeForLoan,
            ];

            $this->view->render($data);
        } catch (Exception $e) {
            $this->dump(['oj' => $e]);
            exit;
        }
    }

    public function new(): void
    {
        if ($this->server['REQUEST_METHOD'] === 'POST') {

            $newRequest = floatval($this->post['loan']);
            if ($newRequest == 1 || !$newRequest) {
                $this->redirect();
            }

            $newLoan = new LoanProposal($newRequest);
            $fee = $this->feeCalculatorService->calculate($newLoan);
            $this->redirect('/', ['feeForLoan' => $fee]);
        }
    }
}
