<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">


                    <div class="row">
                        <div class="top-title-container">
                            <h1>INSTALLER <span>REGISTRATION</span></h1>
                        </div>
                    </div>

                    <div class="row">
                        <form id="new-client-form" method="POST" action="/registration/submit">
                            <div id="wizard">

                                <!------Company Details------->
                                <h3>Company Details</h3>
                                <section>
                                    <div class="section-title">
                                        <h4>Company Details</h4>
                                    </div>
                                    <div class="form-group-container col-md-8 col-md-offset-2">
                                        <div class="form-group">
                                            <label for="campaign_name">Company Name:</label>
                                            <input type="text" name="campaign_name" id="campaign_name" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address:</label>
                                            <input type="text" name="address" id="address" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City:</label>
                                            <input type="text" name="city" id="city" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="state">State:</label>
                                            <input type="text" name="state" id="state" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="abn">ABN:</label>
                                            <input type="text" name="abn" id="abn" value="" class="form-control" />
                                        </div>
                                    </div>
                                </section>

                                <!------Contact Details------->
                                <h3>Contact Details</h3>
                                <section>
                                    <div class="section-title">
                                        <h4>Contact Details</h4>
                                    </div>
                                    <div class="form-group-container col-md-8 col-md-offset-2">
                                        <div class="form-group">
                                            <label for="full_name">Full Name:</label>
                                            <input type="text" name="full_name" id="full_name" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone:</label>
                                            <input type="text" name="phone" id="phone" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" name="email" id="email" value="" class="form-control"/>
                                        </div>
                                    </div>
                                </section>

                                <!------Services------->
                                <h3>Services</h3>
                                <section>
                                    <div class="section-title">
                                        <h4>Our Services</h4>
                                    </div>
                                    <h5>Please select the service(s) you would like to receive leads</h5>
                                    <div class="form-group-container col-md-8 col-md-offset-2">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="SOLAR_PV" id="SOLAR_PV" value="1" />
                                                SOLAR PV
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="HOT_WATER" id="HOT_WATER" value="1" />
                                                HOT WATER
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="AIR_CONDITIONING" id="AIR_CONDITIONING" value="1" />
                                                AIR CONDITIONING
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="ELECTRICIANS" id="ELECTRICIANS" value="1" />
                                                ELECTRICIANS
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="REMOVALS" id="REMOVALS" value="1" />
                                                REMOVALS
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="REALESTATE" id="REALESTATE" value="1" />
                                                REALESTATE
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="INSULATION" id="INSULATION" value="1" />
                                                INSULATION
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="HANDYMAN" id="HANDYMAN" value="1" />
                                                HANDYMAN
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="PAINTERS" id="PAINTERS" value="1" />
                                                PAINTERS
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="PLUMBERS" id="PLUMBERS" value="1" />
                                                PLUMBERS
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="CLEANING SERVICES" id="CLEANING SERVICES" value="1" />
                                                CLEANING SERVICES
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="LAWN_MOWING" id="LAWN_MOWING" value="1" />
                                                LAWN MOWING
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="HOME_LOANS" id="HOME_LOANS" value="1" />
                                                HOME LOANS
                                            </label><br>
                                        </div>
                                    </div>
                                </section>


                                <!------Terms & Conditions------->
                                <h3>Terms & Conditions</h3>
                                <section>
                                    <div class="section-title">
                                        <h4>Terms & Conditions</h4>
                                    </div>
                                    <div class="tnc">
                                        <h2>Agreement</h2>
                                        <p>This agreement is made between you (the Contractor) and The Quote Company (us or we). By completing and signing this agreement, you agree to the terms and conditions contained herein.</p>
                                        <p>On signing up, contractors are required to specify:</p>
                                        <ol>
                                            <li>The type of service they wish to receive leads for</li>
                                            <li>The geographic area where they would like to receive leads</li>
                                            <li>The maximum number of leads they would like to receive per week.</li>
                                        </ol>
                                        <p>The Quote Company will not exceed the requested number of leads, unless authorised by the contractor.</p>
                                        <p>Contractors can amend these variables at any time by calling 1300 722 687 or by emailing enquiries@thequotecompany.com.au.</p>
                                        <p>The Quote Company will endeavor to supply the requested number of leads however; we cannot guarantee that this number will be achieved.</p>
                                        <h2>Acceptance of Leads</h2>
                                        <p>The Quote Company charges for all leads, irrespective of whether or not a sale is concluded between the contractor and the potential customer.</p>
                                        <p>We do not charge a commission on any subsequent sales that are generated by the provided leads.</p>
                                        <p>You may reject a lead if:</p>
                                        <ul>
                                            <li>The lead can not be contacted within seven days, provided that you reject the lead after four business days and before seven days of us providing you with that lead.</li>
                                            <li>The lead can not be contacted via the phone numbers supplied, provided that you reject the lead within two business days of us providing you with that lead.</li>
                                            <li>The lead is not likely to have the service completed within six months, provided that you reject the lead within seven days of us providing you with that lead.</li>
                                            <li>The lead was received via your own marketing activities prior to receiving the lead from us, provided that you reject the lead within two days of us providing you with that lead.</li>
                                            <li>The lead is outside of your specified geographic area, provided that you reject the lead within two days of us providing you with that lead.</li>
                                        </ul>
                                        <p>Rejection of leads is only accepted by clicking on the link in the lead email, within the time periods specified above.<br></p>
                                        <h2>Payment</h2>
                                        <p>You agree to pay us for each lead, irrespective of whether a sale is made. Leads that have been rejected in accordance with the rejection reasons listed above will be credited to the contractor's
                                            invoice.</p>
                                        <p>Invoices are sent via email, fortnightly each Monday.

                                        </p><p>Payment by EFT is required within four days of the invoice being issued. If no payment is received in this time, payment will be deducted from the contractor's nominated credit card.</p>

                                        <p>A 2.5% surcharge applies for Visa/ Mastercard and 3% for AMEX.</p>

                                        <p>Interest at the rate of 15% per annum calculated daily is payable on any overdue amount under this agreement. Any fees, expenses or liabilities incurred in re covering any invoices including but not limited to any legal fees, court costs and disbursements (on a full indemnity basis) and debt collector fees are payable by the contractor.</p>

                                        <h2>Cancellation of Agreement</h2>

                                        <p>In order to cancel the service, contractors need to call 1300 722 687or send an email to enquiries@ thequotecompany.com.au. We will cancel the service immediately after receiving notification from you.</p>

                                        <p>The Quote Company may cancel this agreement if you are in default of any obligation of this agreement, repudiate this agreement, are insolvent, or default on any payment obligation under this agreement.</p>

                                        <p>Once this agreement is cancelled, an invoice will be generated and payment will be debited from the contractor's credit card within three days of notification unless other payment methods have been agreed upon. </p>

                                        <p>Cancellation of this agreement does not release you from the obligation to pay any outstanding fees or from any other obligation under this agreement.</p>

                                        <h2>Privacy</h2>
                                        <p>Contractors agree not to use, distribute, or sell any of the information provided by The Quote Company about the customer other than for providing a quote for the service requested, unless express permission
                                            is granted by the customer.</p>

                                        <h2>Reviews</h2>
                                        <p>You agree to allow us to collect reviews regarding you and the services that you provide, and to post those reviews on our website and otherwise distribute those reviews at our discretion.</p>

                                        <p>To the maximum extent permitted by law, we do not accept any responsibility whatsoever for any injury, loss, or damage that you may suffer, directly or indirectly, as a result of the content of any review mentioned in this clause, irrespective of how that injury, loss, or damage is caused (including where it is caused by negligence, defamation, or falsehood).</p>

                                        <h2>Liability</h2>
                                        <p>The Quote Company is not liable, whether claims are made or not, for loss of profit, economic or financial loss, damages, consequential loss, loss of opportunity or benefit, loss of a right or any other indirect loss suffered by you.</p>

                                        <p>The Quote Company is not liable for any loss caused to the Applicant by reason of strikes, lockouts, fires, riots, war, embargoes, civil commotions, acts of God or any other
                                            activity beyond our control.</p>

                                        <h2>Governing Law</h2>
                                        <p>This agreement is governed by the laws of Western Australia and by signing, you agree to submit to the non exclusive jurisdiction of the Courts of Western Australia.
                                            Registered Office</p>
                                        <p>Level 33, 264 George Street, Sydney, 2000 NSW</p>
                                    </div>
                                    <div>
                                        <p class="info-block">
                                            <span class="glyphicon glyphicon-info-sign"></span>
                                            <i>By completing the information below, and the required payment section, you have agreed to be an authorised representative for your company and agree to the terms and conditions outlined here within.</i>
                                        </p>
                                    </div>
                                    <div class="form-group-container col-md-8">
                                        <div class="form-group">
                                            <label for="authorised_person">Authorised Person:</label>
                                            <input type="text" name="authorised_person" id="authorised_person" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="position">Position:</label>
                                            <input type="text" name="position" id="position" value="" class="form-control"/>
                                        </div>
                                    </div>

                                </section>

                                <h3>Payment Options</h3>
                                <section>
                                    <div class="section-title">
                                        <h4>Backup Payment Method</h4>
                                    </div>
                                    <p class="info-block">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        <i>Please note that your credit card will only be debited if your account is not paid within the agreed time frame outlined in the terms and conditions.</i>
                                    </p>
                                    <div class="form-group-container col-md-8 col-md-offset-2">
                                        <div class="form-group">
                                            <label for="name_on_card">Name on Card:</label>
                                            <input type="text" name="name_on_card" id="name_on_card" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="credit_card_number">Credit Card Number:</label>
                                            <input type="text" name="credit_card_number" id="credit_card_number" value="" class="form-control"/>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="expires_mm">Expires:</label>
                                                <div class="row">
                                                    <div class="col-md-6"><input type="text" name="expires_mm" id="expires_mm" value="" class="form-control required number" placeholder="MM"/></div>
                                                    <div class="col-md-6"><input type="text" name="expires_yy" id="expires_yy" value="" class="form-control required number" placeholder="YY"/></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cvc">CVC:</label>
                                                <input type="text" name="cvc" id="cvc" value="" class="form-control required number" placeholder="999"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group-container col-md-8 col-md-offset-2">
                                            <img src="/img/cc-types.svg">
                                        </div>
                                    </div>

                                </section>

                            </div>
                        </form>
                    </div>


                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->
</main><!-- Page Content -->

<!-- Move it to styles-->
<style>
.top-title-container{
    padding:20px;
    color:#000;
    text-align:center;
    background: #fff;
    border:1px solid #000;
}
.top-title-container h1{
    text-weight:900;
}
.top-title-container span{
    color:#f7911c;
}
.section-title{
    border:1px solid #000;
    text-align:center;
    padding:10px 20px;
}
.section-title h4{
    text-weight:700;
}
.form-group-container{
    margin-top:20px;
}
.tnc{
    margin:20px 0;
    padding: 10px;
    background-color: #fff;
    height: 210px;
    overflow-y: scroll;
}
.info-block{
    padding-left:50px;
    position:relative;
}
.info-block > span{
    position:absolute;
    left:0;
    font-size:40px;
}


/*
Common
*/

.wizard,
.tabcontrol
{
    display: block;
    width: 100%;
    margin-top:20px;
    padding:20px;
    overflow: hidden;
    background: #fff;
}

.wizard a,
.tabcontrol a
{
    outline: 0;
}

.wizard ul,
.tabcontrol ul
{
    list-style: none !important;
    padding: 0;
    margin: 0;
}

.wizard ul > li,
.tabcontrol ul > li
{
    display: block;
    padding: 0;
}

/* Accessibility */
.wizard > .steps .current-info,
.tabcontrol > .steps .current-info
{
    position: absolute;
    left: -999em;
}

.wizard > .content > .title,
.tabcontrol > .content > .title
{
    position: absolute;
    left: -999em;
}



/*
    Wizard
*/

.wizard > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.wizard.vertical > .steps
{
    display: table;
    width: 100%;
}

.wizard > .steps .number
{
    font-size: 1.429em;
}
.wizard > .steps > ul{
    display:table-row;
    width:100% !important;
}

.wizard > .steps > ul > li
{
    display: table-cell;
}

.wizard > .steps > ul > li,
.wizard > .actions > ul > li
{
    float: left;
}

.wizard.vertical > .steps > ul > li
{
    float: none;
    width: 100%;
}

.wizard > .steps a,
.wizard > .steps a:hover,
.wizard > .steps a:active
{
    display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 1em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .steps .disabled a,
.wizard > .steps .disabled a:hover,
.wizard > .steps .disabled a:active
{
    background: #eee;
    color: #aaa;
    cursor: default;
}

.wizard > .steps .current a,
.wizard > .steps .current a:hover,
.wizard > .steps .current a:active
{
    background: #2184be;
    color: #fff;
    cursor: default;
}

.wizard > .steps .done a,
.wizard > .steps .done a:hover,
.wizard > .steps .done a:active
{
    background: #9dc8e2;
    color: #fff;
}

.wizard > .steps .error a,
.wizard > .steps .error a:hover,
.wizard > .steps .error a:active
{
    background: #ff3111;
    color: #fff;
}

.wizard > .content
{
    background: #eee;
    display: block;
    margin: 0.5em;
    min-height: 43em;
    overflow: hidden;
    position: relative;
    width: auto;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard.vertical > .content
{
    display: inline;
    float: left;
    margin: 0 2.5% 0.5em 2.5%;
    width: 65%;
}

.wizard > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.wizard > .content > .body ul
{
    list-style: disc !important;
}

.wizard > .content > .body ul > li
{
    display: list-item;
}

.wizard > .content > .body > iframe
{
    border: 0 none;
    width: 100%;
    height: 100%;
}

.wizard > .content > .body input
{
    display: block;
    border: 1px solid #ccc;
}

.wizard > .content > .body input[type="checkbox"]
{
    display: inline-block;
}

.wizard > .content > .body input.error
{
    background: rgb(251, 227, 228);
    border: 1px solid #fbc2c4;
    color: #8a1f11;
}

.wizard > .content > .body label
{
    display: inline-block;
    margin-bottom: 0.5em;
}

.wizard > .content > .body label.error
{
    color: #8a1f11;
    display: inline-block;
    margin-left: 1.5em;
}

.wizard > .actions
{
    position: relative;
    display: block;
    text-align: right;
    width: 100%;
}

.wizard.vertical > .actions
{
    display: inline;
    float: right;
    margin: 0 2.5%;
    width: 95%;
}

.wizard > .actions > ul
{
    display: inline-block;
    text-align: right;
}

.wizard > .actions > ul > li
{
    margin: 0 0.5em;
}

.wizard.vertical > .actions > ul > li
{
    margin: 0 0 0 1em;
}

.wizard > .actions a,
.wizard > .actions a:hover,
.wizard > .actions a:active
{
    background: #2184be;
    color: #fff;
    display: block;
    padding: 0.5em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .actions .disabled a,
.wizard > .actions .disabled a:hover,
.wizard > .actions .disabled a:active
{
    background: #eee;
    color: #aaa;
}

.wizard > .loading
{
}

.wizard > .loading .spinner
{
}



/*
    Tabcontrol
*/

.tabcontrol > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.tabcontrol > .steps > ul
{
    position: relative;
    margin: 6px 0 0 0;
    top: 1px;
    z-index: 1;
}

.tabcontrol > .steps > ul > li
{
    float: left;
    margin: 5px 2px 0 0;
    padding: 1px;

    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.tabcontrol > .steps > ul > li:hover
{
    background: #edecec;
    border: 1px solid #bbb;
    padding: 0;
}

.tabcontrol > .steps > ul > li.current
{
    background: #fff;
    border: 1px solid #bbb;
    border-bottom: 0 none;
    padding: 0 0 1px 0;
    margin-top: 0;
}

.tabcontrol > .steps > ul > li > a
{
    color: #5f5f5f;
    display: inline-block;
    border: 0 none;
    margin: 0;
    padding: 10px 30px;
    text-decoration: none;
}

.tabcontrol > .steps > ul > li > a:hover
{
    text-decoration: none;
}

.tabcontrol > .steps > ul > li.current > a
{
    padding: 15px 30px 10px 30px;
}

.tabcontrol > .content
{
    position: relative;
    display: inline-block;
    width: 100%;
    height: 35em;
    overflow: hidden;
    border-top: 1px solid #bbb;
    padding-top: 20px;
}

.tabcontrol > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.tabcontrol > .content > .body ul
{
    list-style: disc !important;
}

.tabcontrol > .content > .body ul > li
{
    display: list-item;
}
</style>



