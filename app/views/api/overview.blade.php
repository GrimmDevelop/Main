@extends('layout')

@section('body')
    <div class="alert alert-danger">
        <strong>Danger!</strong> Api is experimental and may change significantly over time!<br>
        If you find any errors, please send them to <a href="{{ Config::get('mail.from.address') }}">{{ Config::get('mail.from.name') }}</a>
    </div>
        <h1>Letters</h1>
        <div class="row">
        <div class="col-md-6">
            <p><strong>Request</strong></p>

            <p>Provide a <a href="#pagination-object">pagination</a> object that includes the total amount of letters, letters per page and the <a href="#letter-object">letter</a> objects.</p>

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
                        <td><code>items_per_page</code></td>
                        <td><em>integer</em></td>
                        <td>number <strong>objects</strong> that will come <strong>per page</strong></td>
                    </tr>
                    <tr>
                        <td><code>page</code></td>
                        <td><em>integer</em></td>
                        <td>page to load</td>
                    </tr>
                    <tr>
                        <td><code>load</code></td>
                        <td><em>array of strings</em></td>
                        <td>array containing parts for eager loading (see <a href="#letter-object">letter</a> object)</loading>
                    </tr>
                    <tr>
                        <td><code>from</code></td>
                        <td><em>integer</em></td>
                        <td>total start <strong>offset</strong> of data objects</td>
                    </tr>
                    <tr>
                        <td><code>to</code></td>
                        <td><em>integer</em></td>
                        <td>total end <strong>offset</strong> of data objects</td>
                    </tr>
                    <tr>
                        <td><code>last_page</code></td>
                        <td><em>array of <a href="#letter-object">letter</a> objects</em></td>
                        <td>array containing all objects</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <p><strong>Request Sample</strong></p>
            <pre><code>GET {{ url('api/letters') }} 
{
    items_per_page: 25,
    page: 1,
    load: [
        "senders"
    ]
}</code></pre>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Response</strong></p>

            <p>The requested <a href="#pagination-object">pagination</a> object that includes the total amount of letters, letters per page and the <a href="#letter-object">letter</a> objects.</p>

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
                        <td><code>total</code></td>
                        <td><em>integer</em></td>
                        <td>total amount of letter found by request</td>
                    </tr>
                    <tr>
                        <td><code>per_page</code></td>
                        <td><em>integer</em></td>
                        <td>number <strong>objects</strong> that will come <strong>per page</strong> (note on the last page may less objects)</td>
                    </tr>
                    <tr>
                        <td><code>current_page</code></td>
                        <td><em>integer</em></td>
                        <td>position of the <strong>current page</strong> starting with 1</td>
                    </tr>
                    <tr>
                        <td><code>last_page</code></td>
                        <td><em>integer</em></td>
                        <td>maximum number of pages</td>
                    </tr>
                    <tr>
                        <td><code>from</code></td>
                        <td><em>integer</em></td>
                        <td>total start <strong>offset</strong> of data objects</td>
                    </tr>
                    <tr>
                        <td><code>to</code></td>
                        <td><em>integer</em></td>
                        <td>total end <strong>offset</strong> of data objects</td>
                    </tr>
                    <tr>
                        <td><code>data</code></td>
                        <td><em>array of <a href="#letter-object">letters</a></em></td>
                        <td>array containing all objects</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <p><strong>Response Sample</strong></p>
            <pre><code>{
    "total":19211,
    "per_page":5,
    "current_page":1,
    "last_page":3843,
    "from":1,
    "to":5,
    "data":[
        {
            "id":1,
            "code":"17880101.00",
            "language":"",
            "date":"1. Januar 1788",
            "from_id":2911007,
            "to_id":2911007,
            "created_at":"2014-10-05 21:04:42",
            "updated_at":"2014-10-06 08:11:30",
            "senders":[
                {
                    "id":2820,
                    "name_2013":"Grimm, Jacob",
                    "created_at":"2014-10-06 07:42:47",
                    "updated_at":"2014-10-06 07:42:47",
                    "pivot":{"letter_id":1,"person_id":2820}
                }
            ]
        },
        {
            "id":2,
            "code":"17890919.00",
            "language":"",
            "date":"[19. September 1789]",
            "from_id":2911007,
            "to_id":2911007,
            "created_at":"2014-10-05 21:04:43",
            "updated_at":"2014-10-06 08:12:45",
            "senders":[
                {
                    "id":2820,
                    "name_2013":"Grimm, Jacob",
                    "created_at":"2014-10-06 07:42:47",
                    "updated_at":"2014-10-06 07:42:47",
                    "pivot":{"letter_id":2,"person_id":2820}
                },
                {
                    "id":2821,
                    "name_2013":"Grimm, Wilhelm",
                    "created_at":"2014-10-06 07:42:47",
                    "updated_at":"2014-10-06 07:42:47",
                    "pivot":{"letter_id":2,"person_id":2821}
                }
            ]
        },
        ...
    ]
}</code></pre>
        </div>
    </div>
@stop