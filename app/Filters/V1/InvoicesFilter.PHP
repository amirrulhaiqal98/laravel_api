<?php

namespace App\Filters\v1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class InvoicesFilter extends ApiFilter {

    private $safeParams = [
        'customerId' => ['eq'],
        'amount' => ['eq', 'gt', 'lt','lte','gte'],
        'status' => ['eq','ne'],
        'billedDate' => ['eq', 'gt', 'lt','lte','gte'],
        'paidDate' => ['eq', 'gt', 'lt','lte','gte']
    ];

    private $columnMap = [
        'customerId' => 'customer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date'
    ];

    private $operatorMap = [
        'eq'  => '=',
        'lt'  => '<',
        'lte' => '<=',
        'gt'  => '>',
        'gte' => '>=',
        'ne'  => '!='
    ];
    
    public function transform(Request $request) {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }

}
