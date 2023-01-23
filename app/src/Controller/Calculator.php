<?php

declare(strict_types=1);

namespace App\Controller;

use App\View;
use App\Controller\AbstractController;
use App\Model\LoanProposal;
use App\Service\Calculator\FeeCalculator;
use App\Service\FeeStructure;

class Calculator extends AbstractController
{
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
        $feeStructure = $this->feeStructureService->getFeeStructure();
        $data = [
            'feeStructure' => $feeStructure,
            'feeForLoan' => $feeForLoan,
        ];

        $this->view->render($data);
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

    public function edit(): void
    {
        if ($this->server['REQUEST_METHOD'] === 'POST') {
            $valuesArray = $this->post;
            $newData = array_filter($valuesArray, function ($value) {
                return $value !== null && $value !== '';
            });

            if (count($newData) === 0) {
                $this->redirect();
            }
            $cleanData = $this->clearData($newData);
            $this->feeStructureService->updateFeeStructure($cleanData);
            $this->redirect();
        }

        $feeStructure = $this->feeStructureService->getFeeStructure();
        $this->view->render(['feeStructure' => $feeStructure], 'edit');
    }
}
