@extends('layout')

@section('body')<div class="row">
                  <div class="col-md-6">

                    <p><strong>Request</strong></p>

                    <p>Provide a <a href="#payment-object">payment</a> object that includes an intent, payer, and transactions.</p>

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

                    <pre class="switch switch-bash prettyprint prettyprinted" style="display: block;" data-active="true"><code class="language-bsh"><span class="pln">curl </span><span class="pun">-</span><span class="pln">v https</span><span class="pun">://</span><span class="pln">api</span><span class="pun">.</span><span class="pln">sandbox</span><span class="pun">.</span><span class="pln">paypal</span><span class="pun">.</span><span class="pln">com</span><span class="pun">/</span><span class="pln">v1</span><span class="pun">/</span><span class="pln">payments</span><span class="pun">/</span><span class="pln">payment \
                </span><span class="pun">-</span><span class="pln">H </span><span class="str">"Content-Type:application/json"</span><span class="pln"> \
                </span><span class="pun">-</span><span class="pln">H </span><span class="str">"Authorization: Bearer &lt;Access-Token&gt;"</span><span class="pln"> \
                </span><span class="pun">-</span><span class="pln">d </span><span class="str">'{
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
                }'</span></code></pre>

                    <pre class="switch switch-ruby prettyprint prettyprinted" style="display: none;"><code class="language-ruby"><span class="lit">@payment</span><span class="pln"> </span><span class="pun">=</span><span class="pln"> </span><span class="typ">Payment</span><span class="pun">.</span><span class="pln">new</span><span class="pun">({</span><span class="pln">
                  </span><span class="pun">:</span><span class="pln">intent </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"sale"</span><span class="pun">,</span><span class="pln">
                  </span><span class="pun">:</span><span class="pln">payer </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                    </span><span class="pun">:</span><span class="pln">payment_method </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"credit_card"</span><span class="pun">,</span><span class="pln">
                    </span><span class="pun">:</span><span class="pln">funding_instruments </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                      </span><span class="pun">:</span><span class="pln">credit_card </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">type </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"visa"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">number </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"4417119669820331"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">expire_month </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"11"</span><span class="pun">,</span><span class="pln"> </span><span class="pun">:</span><span class="pln">expire_year </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"2018"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">cvv2 </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"874"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">first_name </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"Joe"</span><span class="pun">,</span><span class="pln"> </span><span class="pun">:</span><span class="pln">last_name </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"Shopper"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">billing_address </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                          </span><span class="pun">:</span><span class="pln">line1 </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"52 N Main ST"</span><span class="pun">,</span><span class="pln">
                          </span><span class="pun">:</span><span class="pln">city </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"Johnstown"</span><span class="pun">,</span><span class="pln">
                          </span><span class="pun">:</span><span class="pln">state </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"OH"</span><span class="pun">,</span><span class="pln">
                          </span><span class="pun">:</span><span class="pln">postal_code </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"43210"</span><span class="pun">,</span><span class="pln"> </span><span class="pun">:</span><span class="pln">country_code </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"US"</span><span class="pln"> </span><span class="pun">}}}]},</span><span class="pln">
                  </span><span class="pun">:</span><span class="pln">transactions </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                    </span><span class="pun">:</span><span class="pln">amount </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                      </span><span class="pun">:</span><span class="pln">total </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"7.47"</span><span class="pun">,</span><span class="pln">
                      </span><span class="pun">:</span><span class="pln">currency </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"USD"</span><span class="pun">,</span><span class="pln">
                      </span><span class="pun">:</span><span class="pln">details </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">subtotal </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"7.41"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">tax </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">,</span><span class="pln">
                        </span><span class="pun">:</span><span class="pln">shipping </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">}},</span><span class="pln">
                    </span><span class="pun">:</span><span class="pln">description </span><span class="pun">=&gt;</span><span class="pln"> </span><span class="str">"This is the payment transaction description."</span><span class="pln"> </span><span class="pun">}]})</span><span class="pln">

                </span><span class="lit">@payment</span><span class="pun">.</span><span class="pln">create</span></code></pre>
                    <pre class="switch switch-python prettyprint prettyprinted" style="display: none;"><code class="language-python"><span class="pln">payment </span><span class="pun">=</span><span class="pln"> </span><span class="typ">Payment</span><span class="pun">({</span><span class="pln">
                  </span><span class="str">"intent"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"sale"</span><span class="pun">,</span><span class="pln">
                  </span><span class="str">"payer"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                    </span><span class="str">"payment_method"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"credit_card"</span><span class="pun">,</span><span class="pln">
                    </span><span class="str">"funding_instruments"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                      </span><span class="str">"credit_card"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="str">"type"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"visa"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"number"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"4417119669820331"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"expire_month"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"11"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"expire_year"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"2018"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"cvv2"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"874"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"first_name"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Joe"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"last_name"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Shopper"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"billing_address"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                          </span><span class="str">"line1"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"52 N Main ST"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"city"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Johnstown"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"state"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"OH"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"postal_code"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"43210"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"country_code"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"US"</span><span class="pln"> </span><span class="pun">}}}]},</span><span class="pln">
                  </span><span class="str">"transactions"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                    </span><span class="str">"amount"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                      </span><span class="str">"total"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"7.47"</span><span class="pun">,</span><span class="pln">
                      </span><span class="str">"currency"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"USD"</span><span class="pun">,</span><span class="pln">
                      </span><span class="str">"details"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="str">"subtotal"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"7.41"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"tax"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"shipping"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">}},</span><span class="pln">
                    </span><span class="str">"description"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"This is the payment transaction description."</span><span class="pln"> </span><span class="pun">}]})</span><span class="pln">

                payment</span><span class="pun">.</span><span class="pln">create</span><span class="pun">()</span><span class="pln">  </span><span class="com"># return True or False</span></code></pre>
                    <pre class="switch switch-nodejs prettyprint prettyprinted" style="display: none;"><code class="language-nodejs"><span class="kwd">var</span><span class="pln"> payment_details </span><span class="pun">=</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                  </span><span class="str">"intent"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"sale"</span><span class="pun">,</span><span class="pln">
                  </span><span class="str">"payer"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                    </span><span class="str">"payment_method"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"credit_card"</span><span class="pun">,</span><span class="pln">
                    </span><span class="str">"funding_instruments"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                      </span><span class="str">"credit_card"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="str">"type"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"visa"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"number"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"4417119669820331"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"expire_month"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"11"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"expire_year"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"2018"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"cvv2"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"874"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"first_name"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Joe"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"last_name"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Shopper"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"billing_address"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                          </span><span class="str">"line1"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"52 N Main ST"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"city"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"Johnstown"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"state"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"OH"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"postal_code"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"43210"</span><span class="pun">,</span><span class="pln">
                          </span><span class="str">"country_code"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"US"</span><span class="pln"> </span><span class="pun">}}}]},</span><span class="pln">
                  </span><span class="str">"transactions"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[{</span><span class="pln">
                    </span><span class="str">"amount"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                      </span><span class="str">"total"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"7.47"</span><span class="pun">,</span><span class="pln">
                      </span><span class="str">"currency"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"USD"</span><span class="pun">,</span><span class="pln">
                      </span><span class="str">"details"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                        </span><span class="str">"subtotal"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"7.41"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"tax"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">,</span><span class="pln">
                        </span><span class="str">"shipping"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">}},</span><span class="pln">
                    </span><span class="str">"description"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"This is the payment transaction description."</span><span class="pln"> </span><span class="pun">}]};</span><span class="pln">

                paypal_sdk</span><span class="pun">.</span><span class="pln">payment</span><span class="pun">.</span><span class="pln">create</span><span class="pun">(</span><span class="pln">payment_details</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pun">(</span><span class="pln">error</span><span class="pun">,</span><span class="pln"> payment</span><span class="pun">){</span><span class="pln">
                  </span><span class="kwd">if</span><span class="pun">(</span><span class="pln">error</span><span class="pun">){</span><span class="pln">
                    console</span><span class="pun">.</span><span class="pln">error</span><span class="pun">(</span><span class="pln">error</span><span class="pun">);</span><span class="pln">
                  </span><span class="pun">}</span><span class="pln"> </span><span class="kwd">else</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
                    console</span><span class="pun">.</span><span class="pln">log</span><span class="pun">(</span><span class="pln">payment</span><span class="pun">);</span><span class="pln">
                  </span><span class="pun">}</span><span class="pln">
                </span><span class="pun">});</span></code></pre>
                    <pre class="switch switch-java prettyprint prettyprinted" style="display: none;"><code class="language-java"><span class="typ">OAuthTokenCredential</span><span class="pln"> tokenCredential </span><span class="pun">=</span><span class="pln">
                  </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">OAuthTokenCredential</span><span class="pun">(</span><span class="str">"&lt;CLIENT_ID&gt;"</span><span class="pun">,</span><span class="pln"> </span><span class="str">"&lt;CLIENT_SECRET&gt;"</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">String</span><span class="pln"> accessToken </span><span class="pun">=</span><span class="pln"> tokenCredential</span><span class="pun">.</span><span class="pln">getAccessToken</span><span class="pun">();</span><span class="pln">

                </span><span class="typ">Address</span><span class="pln"> billingAddress </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Address</span><span class="pun">();</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">setLine1</span><span class="pun">(</span><span class="str">"52 N Main ST"</span><span class="pun">);</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">setCity</span><span class="pun">(</span><span class="str">"Johnstown"</span><span class="pun">);</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">setCountryCode</span><span class="pun">(</span><span class="str">"US"</span><span class="pun">);</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">setPostalCode</span><span class="pun">(</span><span class="str">"43210"</span><span class="pun">);</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">setState</span><span class="pun">(</span><span class="str">"OH"</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">CreditCard</span><span class="pln"> creditCard </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">CreditCard</span><span class="pun">();</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setNumber</span><span class="pun">(</span><span class="str">"4417119669820331"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setType</span><span class="pun">(</span><span class="str">"visa"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setExpireMonth</span><span class="pun">(</span><span class="str">"11"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setExpireYear</span><span class="pun">(</span><span class="str">"2018"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setCvv2</span><span class="pun">(</span><span class="str">"874"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setFirstName</span><span class="pun">(</span><span class="str">"Joe"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setLastName</span><span class="pun">(</span><span class="str">"Shopper"</span><span class="pun">);</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">setBillingAddress</span><span class="pun">(</span><span class="pln">billingAddress</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">AmountDetails</span><span class="pln"> amountDetails </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">AmountDetails</span><span class="pun">();</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">setSubtotal</span><span class="pun">(</span><span class="str">"7.41"</span><span class="pun">);</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">setTax</span><span class="pun">(</span><span class="str">"0.03"</span><span class="pun">);</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">setShipping</span><span class="pun">(</span><span class="str">"0.03"</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Amount</span><span class="pln"> amount </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Amount</span><span class="pun">();</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">setTotal</span><span class="pun">(</span><span class="str">"7.47"</span><span class="pun">);</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">setCurrency</span><span class="pun">(</span><span class="str">"USD"</span><span class="pun">);</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">setDetails</span><span class="pun">(</span><span class="pln">amountDetails</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Transaction</span><span class="pln"> transaction </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Transaction</span><span class="pun">();</span><span class="pln">
                transaction</span><span class="pun">.</span><span class="pln">setAmount</span><span class="pun">(</span><span class="pln">amount</span><span class="pun">);</span><span class="pln">
                transaction</span><span class="pun">.</span><span class="pln">setDescription</span><span class="pun">(</span><span class="str">"This is the payment transaction description."</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">Transaction</span><span class="pun">&gt;</span><span class="pln"> transactions </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">ArrayList</span><span class="pun">&lt;</span><span class="typ">Transaction</span><span class="pun">&gt;();</span><span class="pln">
                transactions</span><span class="pun">.</span><span class="pln">add</span><span class="pun">(</span><span class="pln">transaction</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">FundingInstrument</span><span class="pln"> fundingInstrument </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">FundingInstrument</span><span class="pun">();</span><span class="pln">
                fundingInstrument</span><span class="pun">.</span><span class="pln">setCreditCard</span><span class="pun">(</span><span class="pln">creditCard</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">FundingInstrument</span><span class="pun">&gt;</span><span class="pln"> fundingInstruments </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">ArrayList</span><span class="pun">&lt;</span><span class="typ">FundingInstrument</span><span class="pun">&gt;();</span><span class="pln">
                fundingInstruments</span><span class="pun">.</span><span class="pln">add</span><span class="pun">(</span><span class="pln">fundingInstrument</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Payer</span><span class="pln"> payer </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payer</span><span class="pun">();</span><span class="pln">
                payer</span><span class="pun">.</span><span class="pln">setFundingInstruments</span><span class="pun">(</span><span class="pln">fundingInstruments</span><span class="pun">);</span><span class="pln">
                payer</span><span class="pun">.</span><span class="pln">setPaymentMethod</span><span class="pun">(</span><span class="str">"credit_card"</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Payment</span><span class="pln"> payment </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payment</span><span class="pun">();</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">setIntent</span><span class="pun">(</span><span class="str">"sale"</span><span class="pun">);</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">setPayer</span><span class="pun">(</span><span class="pln">payer</span><span class="pun">);</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">setTransactions</span><span class="pun">(</span><span class="pln">transactions</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Payment</span><span class="pln"> createdPayment </span><span class="pun">=</span><span class="pln"> payment</span><span class="pun">.</span><span class="pln">create</span><span class="pun">(</span><span class="pln">accessToken</span><span class="pun">);</span></code></pre>
                    <pre class="switch switch-php prettyprint prettyprinted" style="display: none;"><code class="language-php"><span class="pln">$apiContext </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">ApiContext</span><span class="pun">(</span><span class="kwd">new</span><span class="pln"> </span><span class="typ">OAuthTokenCredential</span><span class="pun">(</span><span class="pln">
                		</span><span class="str">"&lt;CLIENT_ID&gt;"</span><span class="pun">,</span><span class="pln"> </span><span class="str">"&lt;CLIENT_SECRET&gt;"</span><span class="pun">));</span><span class="pln">

                $addr </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Address</span><span class="pun">();</span><span class="pln">
                $addr</span><span class="pun">-&gt;</span><span class="pln">setLine1</span><span class="pun">(</span><span class="str">'52 N Main ST'</span><span class="pun">);</span><span class="pln">
                $addr</span><span class="pun">-&gt;</span><span class="pln">setCity</span><span class="pun">(</span><span class="str">'Johnstown'</span><span class="pun">);</span><span class="pln">
                $addr</span><span class="pun">-&gt;</span><span class="pln">setCountry_code</span><span class="pun">(</span><span class="str">'US'</span><span class="pun">);</span><span class="pln">
                $addr</span><span class="pun">-&gt;</span><span class="pln">setPostal_code</span><span class="pun">(</span><span class="str">'43210'</span><span class="pun">);</span><span class="pln">
                $addr</span><span class="pun">-&gt;</span><span class="pln">setState</span><span class="pun">(</span><span class="str">'OH'</span><span class="pun">);</span><span class="pln">

                $card </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">CreditCard</span><span class="pun">();</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setNumber</span><span class="pun">(</span><span class="str">'4417119669820331'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setType</span><span class="pun">(</span><span class="str">'visa'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setExpire_month</span><span class="pun">(</span><span class="str">'11'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setExpire_year</span><span class="pun">(</span><span class="str">'2018'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setCvv2</span><span class="pun">(</span><span class="str">'874'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setFirst_name</span><span class="pun">(</span><span class="str">'Joe'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setLast_name</span><span class="pun">(</span><span class="str">'Shopper'</span><span class="pun">);</span><span class="pln">
                $card</span><span class="pun">-&gt;</span><span class="pln">setBilling_address</span><span class="pun">(</span><span class="pln">$addr</span><span class="pun">);</span><span class="pln">

                $fi </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">FundingInstrument</span><span class="pun">();</span><span class="pln">
                $fi</span><span class="pun">-&gt;</span><span class="pln">setCredit_card</span><span class="pun">(</span><span class="pln">$card</span><span class="pun">);</span><span class="pln">

                $payer </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payer</span><span class="pun">();</span><span class="pln">
                $payer</span><span class="pun">-&gt;</span><span class="pln">setPayment_method</span><span class="pun">(</span><span class="str">'credit_card'</span><span class="pun">);</span><span class="pln">
                $payer</span><span class="pun">-&gt;</span><span class="pln">setFunding_instruments</span><span class="pun">(</span><span class="pln">array</span><span class="pun">(</span><span class="pln">$fi</span><span class="pun">));</span><span class="pln">

                $amountDetails </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">AmountDetails</span><span class="pun">();</span><span class="pln">
                $amountDetails</span><span class="pun">-&gt;</span><span class="pln">setSubtotal</span><span class="pun">(</span><span class="str">'7.41'</span><span class="pun">);</span><span class="pln">
                $amountDetails</span><span class="pun">-&gt;</span><span class="pln">setTax</span><span class="pun">(</span><span class="str">'0.03'</span><span class="pun">);</span><span class="pln">
                $amountDetails</span><span class="pun">-&gt;</span><span class="pln">setShipping</span><span class="pun">(</span><span class="str">'0.03'</span><span class="pun">);</span><span class="pln">

                $amount </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Amount</span><span class="pun">();</span><span class="pln">
                $amount</span><span class="pun">-&gt;</span><span class="pln">setCurrency</span><span class="pun">(</span><span class="str">'USD'</span><span class="pun">);</span><span class="pln">
                $amount</span><span class="pun">-&gt;</span><span class="pln">setTotal</span><span class="pun">(</span><span class="str">'7.47'</span><span class="pun">);</span><span class="pln">
                $amount</span><span class="pun">-&gt;</span><span class="pln">setDetails</span><span class="pun">(</span><span class="pln">$amountDetails</span><span class="pun">);</span><span class="pln">

                $transaction </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Transaction</span><span class="pun">();</span><span class="pln">
                $transaction</span><span class="pun">-&gt;</span><span class="pln">setAmount</span><span class="pun">(</span><span class="pln">$amount</span><span class="pun">);</span><span class="pln">
                $transaction</span><span class="pun">-&gt;</span><span class="pln">setDescription</span><span class="pun">(</span><span class="str">'This is the payment transaction description.'</span><span class="pun">);</span><span class="pln">

                $payment </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payment</span><span class="pun">();</span><span class="pln">
                $payment</span><span class="pun">-&gt;</span><span class="pln">setIntent</span><span class="pun">(</span><span class="str">'sale'</span><span class="pun">);</span><span class="pln">
                $payment</span><span class="pun">-&gt;</span><span class="pln">setPayer</span><span class="pun">(</span><span class="pln">$payer</span><span class="pun">);</span><span class="pln">
                $payment</span><span class="pun">-&gt;</span><span class="pln">setTransactions</span><span class="pun">(</span><span class="pln">array</span><span class="pun">(</span><span class="pln">$transaction</span><span class="pun">));</span><span class="pln">

                $payment</span><span class="pun">-&gt;</span><span class="pln">create</span><span class="pun">(</span><span class="pln">$apiContext</span><span class="pun">);</span></code></pre>
                    <pre class="switch switch-csharp prettyprint prettyprinted" style="display: none;"><code class="language-csharp"><span class="typ">OAuthTokenCredential</span><span class="pln"> tokenCredential </span><span class="pun">=</span><span class="pln">
                  </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">OAuthTokenCredential</span><span class="pun">(</span><span class="str">"&lt;CLIENT_ID&gt;"</span><span class="pun">,</span><span class="pln"> </span><span class="str">"&lt;CLIENT_SECRET&gt;"</span><span class="pun">);</span><span class="pln">

                </span><span class="kwd">string</span><span class="pln"> accessToken </span><span class="pun">=</span><span class="pln"> tokenCredential</span><span class="pun">.</span><span class="typ">GetAccessToken</span><span class="pun">();</span><span class="pln">

                </span><span class="typ">Address</span><span class="pln"> billingAddress </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Address</span><span class="pun">();</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">line1 </span><span class="pun">=</span><span class="pln"> </span><span class="str">"52 N Main ST"</span><span class="pun">;</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">city </span><span class="pun">=</span><span class="pln"> </span><span class="str">"Johnstown"</span><span class="pun">;</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">country_code </span><span class="pun">=</span><span class="pln"> </span><span class="str">"US"</span><span class="pun">;</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">postal_code </span><span class="pun">=</span><span class="pln"> </span><span class="str">"43210"</span><span class="pun">;</span><span class="pln">
                billingAddress</span><span class="pun">.</span><span class="pln">state </span><span class="pun">=</span><span class="pln"> </span><span class="str">"OH"</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">CreditCard</span><span class="pln"> creditCard </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">CreditCard</span><span class="pun">();</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">number </span><span class="pun">=</span><span class="pln"> </span><span class="str">"4417119669820331"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">type </span><span class="pun">=</span><span class="pln"> </span><span class="str">"visa"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">expire_month </span><span class="pun">=</span><span class="pln"> </span><span class="str">"11"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">expire_year </span><span class="pun">=</span><span class="pln"> </span><span class="str">"2018"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">cvv2 </span><span class="pun">=</span><span class="pln"> </span><span class="str">"874"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">first_name </span><span class="pun">=</span><span class="pln"> </span><span class="str">"Joe"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">last_name </span><span class="pun">=</span><span class="pln"> </span><span class="str">"Shopper"</span><span class="pun">;</span><span class="pln">
                creditCard</span><span class="pun">.</span><span class="pln">billing_address </span><span class="pun">=</span><span class="pln"> billingAddress</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">AmountDetails</span><span class="pln"> amountDetails </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">AmountDetails</span><span class="pun">();</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">subtotal </span><span class="pun">=</span><span class="pln"> </span><span class="str">"7.41"</span><span class="pun">;</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">tax </span><span class="pun">=</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">;</span><span class="pln">
                amountDetails</span><span class="pun">.</span><span class="pln">shipping </span><span class="pun">=</span><span class="pln"> </span><span class="str">"0.03"</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">Amount</span><span class="pln"> amount </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Amount</span><span class="pun">();</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">total </span><span class="pun">=</span><span class="pln"> </span><span class="str">"7.47"</span><span class="pun">;</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">currency </span><span class="pun">=</span><span class="pln"> </span><span class="str">"USD"</span><span class="pun">;</span><span class="pln">
                amount</span><span class="pun">.</span><span class="pln">details </span><span class="pun">=</span><span class="pln"> amountDetails</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">Transaction</span><span class="pln"> transaction </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Transaction</span><span class="pun">();</span><span class="pln">
                transaction</span><span class="pun">.</span><span class="pln">amount </span><span class="pun">=</span><span class="pln"> amount</span><span class="pun">;</span><span class="pln">
                transaction</span><span class="pun">.</span><span class="pln">description </span><span class="pun">=</span><span class="pln"> </span><span class="str">"This is the payment transaction description."</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">Transaction</span><span class="pun">&gt;</span><span class="pln"> transactions </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">Transaction</span><span class="pun">&gt;();</span><span class="pln">
                transactions</span><span class="pun">.</span><span class="typ">Add</span><span class="pun">(</span><span class="pln">transaction</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">FundingInstrument</span><span class="pln"> fundingInstrument </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">FundingInstrument</span><span class="pun">();</span><span class="pln">
                fundingInstrument</span><span class="pun">.</span><span class="pln">credit_card </span><span class="pun">=</span><span class="pln"> creditCard</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">FundingInstrument</span><span class="pun">&gt;</span><span class="pln"> fundingInstruments </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">List</span><span class="pun">&lt;</span><span class="typ">FundingInstrument</span><span class="pun">&gt;();</span><span class="pln">
                fundingInstruments</span><span class="pun">.</span><span class="typ">Add</span><span class="pun">(</span><span class="pln">fundingInstrument</span><span class="pun">);</span><span class="pln">

                </span><span class="typ">Payer</span><span class="pln"> payer </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payer</span><span class="pun">();</span><span class="pln">
                payer</span><span class="pun">.</span><span class="pln">funding_instruments </span><span class="pun">=</span><span class="pln"> fundingInstruments</span><span class="pun">;</span><span class="pln">
                payer</span><span class="pun">.</span><span class="pln">payment_method      </span><span class="pun">=</span><span class="pln"> </span><span class="str">"credit_card"</span><span class="pln">

                </span><span class="typ">Payment</span><span class="pln"> payment </span><span class="pun">=</span><span class="pln"> </span><span class="kwd">new</span><span class="pln"> </span><span class="typ">Payment</span><span class="pun">();</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">intent </span><span class="pun">=</span><span class="pln"> </span><span class="str">"sale"</span><span class="pun">;</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">payer  </span><span class="pun">=</span><span class="pln"> payer</span><span class="pun">;</span><span class="pln">
                payment</span><span class="pun">.</span><span class="pln">transaction </span><span class="pun">=</span><span class="pln"> transactions</span><span class="pun">;</span><span class="pln">

                </span><span class="typ">Payment</span><span class="pln"> createdPayment </span><span class="pun">=</span><span class="pln"> payment</span><span class="pun">.</span><span class="typ">Create</span><span class="pun">(</span><span class="pln">accessToken</span><span class="pun">);</span></code></pre>
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