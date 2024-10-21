@extends('layout.site')
@section('content')

<div class="card">
    <div class="card-body" style="background-color: black;">
        <h3 class="card-title" style="color: 000;">Transactions</h3>
        <div class="table-responsive">
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th scope="col">Game Name</th>
                        <th scope="col">Available Balance</th>
                        <th scope="col">Transaction Amount</th>
                        <th scope="col">Transaction Type</th>
                        <th scope="col">Slot</th>
                        <th scope="col">Current Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{$transaction->game_name??""}}</td>
                        <td>{{$transaction->current_balance}}</td>
                        <td>{{$transaction->transaction_amount}}</td>
                        <td>{{$transaction->transaction_type == 1?"Debit":"Credit"}}</td>
                        <td>{{$transaction->slot}}</td>
                        <td>{{$transaction->current_balance-$transaction->transaction_amount??0}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
    <br>
</div>
@stop