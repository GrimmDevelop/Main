@extends('layout')

@section('body')
<div class="row">
<div class="col-md-6">


<p><strong>Request</strong></p>

<p>Provide a <a href="#pagination-object">pagination</a> object that includes an intent, payer, and transactions.</p>

<table class="table">
    <thead>
        <tr>
            <th>Property</th>
            <th>Type</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>intent</code></td>
            <td><em>string</em></td>
            <td>Payment intent. Must be set to <code>sale</code> for immediate payment, <code>authorize</code> to <a href="../integration/direct/capture-payment/">authorize a payment for capture later</a>, or <code>order</code> to <a href="#create-an-order">create an order</a>. <strong>Required.</strong>
            </td>
        </tr>
        <tr>
            <td><code>payer</code></td>
            <td><em><a href="#payer-object">payer</a></em></td>
            <td>Source of the funds for this payment represented by a PayPal account or a credit card. <strong>Required.</strong>
            </td>
        </tr>
        <tr>
            <td><code>transactions</code></td>
            <td><em>array of <a href="#transaction-object">transaction</a> objects</em></td>
            <td>Transactional details including the amount and item details. <strong>Required.</strong>
            </td>
        </tr>
        <tr>
            <td><code>redirect_urls</code></td>
            <td><em><a href="#redirecturls-object">redirect_urls</a></em></td>
            <td>Set of redirect URLs you provide only for PayPal-based payments. <strong>Required for PayPal payments.</strong>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>

<!-- END CONTENT/START CODE 1 -->

</div>
<div class="col-md-6">

<p><strong>Request Sample</strong></p>

<!-- CURL CODE -->

<pre><code>curl -v https://api.sandbox.paypal.com/v1/payments/payment \
-H "Content-Type:application/json" \
-H "Authorization: Bearer &lt;Access-Token&gt;" \
-d '{
    "intent":"sale",
    "payer":{
        "payment_method":"credit_card",
        "funding_instruments":[
            {
                "credit_card":{
                    "number":"4417119669820331",
                    "type":"visa",
                    "expire_month":11,
                    "expire_year":2018,
                    "cvv2":"874",
                    "first_name":"Betsy",
                    "last_name":"Buyer",
                    "billing_address":{
                        "line1":"111 First Street",
                        "city":"Saratoga",
                        "state":"CA",
                        "postal_code":"95070",
                        "country_code":"US"
                    }
                }
            }
        ]
    },
    "transactions":[
        {
            "amount":{
                "total":"7.47",
                "currency":"USD",
                "details":{
                    "subtotal":"7.41",
                    "tax":"0.03",
                    "shipping":"0.03"
                }
            },
            "description":"This is the payment transaction description."
        }
    ]
}'</code></pre>
</div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <strong>Danger!</strong> Api is experimental and may change significantly over time!
            </div>

            <h1>Letters</h1>
            <p>There're to different ways to reveice letter: as pagination or as stream</p>
            <h2>Paginator</h2>
            <p><pre>GET {{ url('api/letters') }} {
    items_per_page: 25,
    page: 1
}</pre></p>
            <p><code>parameters: items_per_page, page</code></p>
            <p>receive a paginator json object</p>
            <p><code>{"total":0,"per_page":25,"current_page":1,"last_page":1,"from":1,"to":1,"data":[]}</code></p>
            <p><strong>total</strong> is the total number of objects</p>
            <p><strong>per_page</strong> is the number of objects per page (and also in data array)</p>
            <p><strong>current_page</strong> is the current page</p>
            <p><strong>last_page</strong> is the last page</p>
            <p><strong>from</strong> is the total start offset of data objects</p>
            <p><strong>to</strong> is the total end offset of data objects</p>
            <p><strong>data</strong> is the array containing all objects</p>
        </div>
    </div>
@stop