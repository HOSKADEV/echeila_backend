<?php

return [
    'transaction' => 'Transaction',
    'transactions' => 'Transactions',
    'id' => 'Transaction ID',
    'amount' => 'Amount',
    'type' => 'Type',
    'description' => 'Description',
    'date' => 'Date',
    'status' => 'Status',

    // Transaction Types
    'types' => [
        'credit' => 'Credit',
        'debit' => 'Debit',
        'deposit' => 'Deposit',
        'withdrawal' => 'Withdrawal',
        'refund' => 'Refund',
        'reservation' => 'Reservation',
        'subscription' => 'Subscription',
        'service' => 'Service',
    ],

    // Transaction Status
    'completed' => 'Completed',
    'pending' => 'Pending',
    'failed' => 'Failed',
    'cancelled' => 'Cancelled',
];
