<?php
function send_m($clientEmail, $p, $name, $alttext = '')
{
  $mail = new PHPMailer;
//  $mail->isSendmail();
  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
  // 1 = errors and messages
  // 2 = messages only
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->Host       = "mail.smtp2go.com"; // sets the SMTP server
  $mail->Port       = 2525;                    // set the SMTP port for the GMAIL server
  $mail->Username   = "leads@energysmart.com.au"; // SMTP account username
  $mail->Password   = "FdSrcUgCG46zHS5x";        // SMTP account password

  $mail->isHTML(true);

  $mail->AddReplyTo("leads@energysmart.com.au", "New Qualified Lead");

  $mail->SetFrom('leads@energysmart.com.au', 'New Qualified Lead');

  $mail->AddAddress($clientEmail);

  $mail->Subject = "New Qualified Lead - Please contact ASAP";

  $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  // optional, comment out and test

  $content = <<<EOD
<style type="text/css">
@media only screen and (max-width: 596px) {
  .small-float-center {
    margin: 0 auto !important; float: none !important; text-align: center !important;
  }
  .small-text-center {
    text-align: center !important;
  }
  .small-text-left {
    text-align: left !important;
  }
  .small-text-right {
    text-align: right !important;
  }
  .hide-for-large {
    display: block !important; width: auto !important; overflow: visible !important; max-height: none !important; font-size: inherit !important; line-height: inherit !important;
  }
  table.body table.container .hide-for-large {
    display: table !important; width: 100% !important;
  }
  table.body table.container .row.hide-for-large {
    display: table !important; width: 100% !important;
  }
  table.body table.container .callout-inner.hide-for-large {
    display: table-cell !important; width: 100% !important;
  }
  table.body table.container .show-for-large {
    display: none !important; width: 0; mso-hide: all; overflow: hidden;
  }
  table.body img {
    width: auto; height: auto;
  }
  table.body center {
    min-width: 0 !important;
  }
  table.body .container {
    width: 95% !important;
  }
  table.body .columns {
    height: auto !important; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; padding-left: 16px !important; padding-right: 16px !important;
  }
  table.body .column {
    height: auto !important; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; padding-left: 16px !important; padding-right: 16px !important;
  }
  table.body .columns .column {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  table.body .columns .columns {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  table.body .column .column {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  table.body .column .columns {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  table.body .collapse .columns {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  table.body .collapse .column {
    padding-left: 0 !important; padding-right: 0 !important;
  }
  td.small-1 {
    display: inline-block !important; width: 8.33333% !important;
  }
  th.small-1 {
    display: inline-block !important; width: 8.33333% !important;
  }
  td.small-2 {
    display: inline-block !important; width: 16.66667% !important;
  }
  th.small-2 {
    display: inline-block !important; width: 16.66667% !important;
  }
  td.small-3 {
    display: inline-block !important; width: 25% !important;
  }
  th.small-3 {
    display: inline-block !important; width: 25% !important;
  }
  td.small-4 {
    display: inline-block !important; width: 33.33333% !important;
  }
  th.small-4 {
    display: inline-block !important; width: 33.33333% !important;
  }
  td.small-5 {
    display: inline-block !important; width: 41.66667% !important;
  }
  th.small-5 {
    display: inline-block !important; width: 41.66667% !important;
  }
  td.small-6 {
    display: inline-block !important; width: 50% !important;
  }
  th.small-6 {
    display: inline-block !important; width: 50% !important;
  }
  td.small-7 {
    display: inline-block !important; width: 58.33333% !important;
  }
  th.small-7 {
    display: inline-block !important; width: 58.33333% !important;
  }
  td.small-8 {
    display: inline-block !important; width: 66.66667% !important;
  }
  th.small-8 {
    display: inline-block !important; width: 66.66667% !important;
  }
  td.small-9 {
    display: inline-block !important; width: 75% !important;
  }
  th.small-9 {
    display: inline-block !important; width: 75% !important;
  }
  td.small-10 {
    display: inline-block !important; width: 83.33333% !important;
  }
  th.small-10 {
    display: inline-block !important; width: 83.33333% !important;
  }
  td.small-11 {
    display: inline-block !important; width: 91.66667% !important;
  }
  th.small-11 {
    display: inline-block !important; width: 91.66667% !important;
  }
  td.small-12 {
    display: inline-block !important; width: 100% !important;
  }
  th.small-12 {
    display: inline-block !important; width: 100% !important;
  }
  .columns td.small-12 {
    display: block !important; width: 100% !important;
  }
  .column td.small-12 {
    display: block !important; width: 100% !important;
  }
  .columns th.small-12 {
    display: block !important; width: 100% !important;
  }
  .column th.small-12 {
    display: block !important; width: 100% !important;
  }
  table.body td.small-offset-1 {
    margin-left: 8.33333% !important;
  }
  table.body th.small-offset-1 {
    margin-left: 8.33333% !important;
  }
  table.body td.small-offset-2 {
    margin-left: 16.66667% !important;
  }
  table.body th.small-offset-2 {
    margin-left: 16.66667% !important;
  }
  table.body td.small-offset-3 {
    margin-left: 25% !important;
  }
  table.body th.small-offset-3 {
    margin-left: 25% !important;
  }
  table.body td.small-offset-4 {
    margin-left: 33.33333% !important;
  }
  table.body th.small-offset-4 {
    margin-left: 33.33333% !important;
  }
  table.body td.small-offset-5 {
    margin-left: 41.66667% !important;
  }
  table.body th.small-offset-5 {
    margin-left: 41.66667% !important;
  }
  table.body td.small-offset-6 {
    margin-left: 50% !important;
  }
  table.body th.small-offset-6 {
    margin-left: 50% !important;
  }
  table.body td.small-offset-7 {
    margin-left: 58.33333% !important;
  }
  table.body th.small-offset-7 {
    margin-left: 58.33333% !important;
  }
  table.body td.small-offset-8 {
    margin-left: 66.66667% !important;
  }
  table.body th.small-offset-8 {
    margin-left: 66.66667% !important;
  }
  table.body td.small-offset-9 {
    margin-left: 75% !important;
  }
  table.body th.small-offset-9 {
    margin-left: 75% !important;
  }
  table.body td.small-offset-10 {
    margin-left: 83.33333% !important;
  }
  table.body th.small-offset-10 {
    margin-left: 83.33333% !important;
  }
  table.body td.small-offset-11 {
    margin-left: 91.66667% !important;
  }
  table.body th.small-offset-11 {
    margin-left: 91.66667% !important;
  }
  table.body table.columns td.expander {
    display: none !important;
  }
  table.body table.columns th.expander {
    display: none !important;
  }
  table.body .right-text-pad {
    padding-left: 10px !important;
  }
  table.body .text-pad-right {
    padding-left: 10px !important;
  }
  table.body .left-text-pad {
    padding-right: 10px !important;
  }
  table.body .text-pad-left {
    padding-right: 10px !important;
  }
  table.menu {
    width: 100% !important;
  }
  table.menu td {
    width: auto !important; display: inline-block !important;
  }
  table.menu th {
    width: auto !important; display: inline-block !important;
  }
  table.menu.vertical td {
    display: block !important;
  }
  table.menu.vertical th {
    display: block !important;
  }
  table.menu.small-vertical td {
    display: block !important;
  }
  table.menu.small-vertical th {
    display: block !important;
  }
  table.menu[align="center"] {
    width: auto !important;
  }
  table.button.small-expand {
    width: 100% !important;
  }
  table.button.small-expanded {
    width: 100% !important;
  }
  table.button.small-expand table {
    width: 100%;
  }
  table.button.small-expanded table {
    width: 100%;
  }
  table.button.small-expand table a {
    text-align: center !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important;
  }
  table.button.small-expanded table a {
    text-align: center !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important;
  }
  table.button.small-expand center {
    min-width: 0;
  }
  table.button.small-expanded center {
    min-width: 0;
  }
}
</style>
    <!-- <style> -->
<table class="body body-style" data-made-with-foundation=""
       style="background-color: #f3f3f3 !important; border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; height: 100%; width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"
       bgcolor="#f3f3f3 !important">
  <tbody>
  <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
    <td class="float-center" align="center" valign="top"
        style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; float: none; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0;">
      <center data-parsed="" style="width: 100%; min-width: 580px;">
        <table class="spacer float-center"
               style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: center; width: 100%; float: none; margin: 0 auto; padding: 0;">
          <tbody>
          <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
            <td height="16px"
                style="font-size: 16px; line-height: 16px; word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; mso-line-height-rule: exactly; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; margin: 0; padding: 0;"
                align="left" valign="top">&nbsp;</td>
          </tr>
          </tbody>
        </table>
        <table align="center" class="container float-center body-ground"
               style="background-color: rgba(241, 237, 237, 0.72) !important; border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: center; width: 580px; float: none; margin: 0 auto; padding: 0;"
               bgcolor="rgba(241, 237, 237, 0.72) !important">
          <tbody>
          <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
            <td
              style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"
              align="left" valign="top">

              <table class="row"
                     style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">
                <tbody>
                <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                  <th class="small-12 large-12 columns first last body-wrapper"
                      style="width: 564px; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 0px 16px;"
                      align="left">
                    <table
                      style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">
                      <tbody>
                      <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                        <th
                          style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"
                          align="left">
                          <table align="center" class="row rowfor-header"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; background: #636365; padding: 0;"
                                 bgcolor="#636365">
                            <tbody>
                            <tr>
                              <th class="small-12 large-12 columns text-center first last"
                                  style="width: 100%; text-align: center; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 0 16px;"
                                  align="center">
                                <img src="http://www.energysmart.com.au/edm-quote/images/uploads/logo1.png" alt=""
                                     align="center" class="float-center"
                                     style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; clear: both; display: block; float: none; text-align: center; margin: 0 auto;">
                              </th>

                            </tr>
                            </tbody>
                          </table>
                          <table class="row rowfor-header"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; background: #636365; padding: 0;"
                                 bgcolor="#636365">
                            <tbody>
                            <tr>
                              <th class="small-12 large-12 columns first last title-text"
                                  style="width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 10px 0 0px;"
                                  align="left">
                                <p class="text-center title-words"
                                   style="color: #fff !important; font-weight: bold; text-align: center; font-family: Helvetica, Arial, sans-serif; line-height: 1.3; font-size: 16px; margin: 0 0 10px; padding: 0;"
                                   align="center">New Qualified Lead in your area</p>
                              </th>
                            </tr>
                            </tbody>
                          </table>
                          <table class="row"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">
                            <tbody>
                            <tr>
                              <th class="small-12 large-12 columns first last"
                                  style="width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 0 16px;"
                                  align="left">
                                <p class="introduction top-introduction"
                                   style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 0 0px; padding: 15px 16px 0;"
                                   align="left">Hi, $name </p>
                              </th>
                            </tr>
                            </tbody>
                          </table>
                          <table class="row"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: table; padding: 0;">
                            <tbody>
                            <tr>
                              <th class="small-12 large-12 columns first last"
                                  style="width: 100%; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 0 0 16px;"
                                  align="left">
                                <p class="introduction"
                                   style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 0 0px; padding: 0 16px;"
                                   align="left">We have a new qualified lead in your area. Please see below and contact
                                  ASAP:</p>
                              </th>
                            </tr>
                            </tbody>
                          </table>
                          <table class="spacer"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; padding: 0;">
                            <tbody>
                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                              <td height="16px"
                                  style="font-size: 16px; line-height: 16px; word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; mso-line-height-rule: exactly; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; margin: 0; padding: 0;"
                                  align="left" valign="top">&nbsp;</td>
                            </tr>
                            </tbody>
                          </table>
                          <table class="callout"
                                 style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; margin-bottom: 16px; padding: 0;">
                            <tbody>
                            <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                              <th class="callout-inner secondary"
                                  style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; width: 100%; margin: 0; padding: 10px;"
                                  align="left">
EOD;
  foreach ($p as $v) {
    $content .= '

                                <table class="row tables-borders cell2"
                                       style="border-collapse: collapse !important; border-spacing: 0 !important; display: table !important; position: relative !important; vertical-align: top; text-align: left; width: 100%; padding: 0; border: 1px ridge #636365;">
                                  <tbody>
                                  <tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
                                    <th class="small-5 large-4 columns first cell text-center right-border"
                                        style="border-right-width: 1px; border-right-color: #636365; border-right-style: ridge; width: 33.33333%; text-align: center; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 10px 0 0px;"
                                        align="center">
                                      <p class="table-styles"
                                         style="text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 0 10px; padding: 0 0 0 15px;"
                                         align="left">' . $v["field_name"] . '</p>
                                    </th>
                                    <th class="small-7 large-8 columns last cell text-center"
                                        style="width: 66.66667%; text-align: center; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 auto; padding: 10px 0 0px;"
                                        align="center">
                                      <p class="table-styles"
                                         style="text-align: left; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; font-size: 16px; margin: 0 0 10px; padding: 0 0 0 15px;"
                                         align="left">' . $v["val"] . '</p>
                                    </th>
                                  </tr>
                                  </tbody>
                                </table>';
  }

$content .= <<<EOD
                              </th>
                              <th class="expander"
                                  style="visibility: hidden; width: 0; color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0; padding: 0;"
                                  align="left"></th>
                            </tr>
                            </tbody>
                          </table>
                          <hr>
                          <p class="introduction"
                             style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-align: left; line-height: 1.3; font-size: 16px; margin: 0 0 0px; padding: 0 16px;"
                             align="left">Please contact this lead immediately, the consumer is waiting to hear from
                            you. Please keep working this lead until contact is made. If you need to request a return of
                            this lead, please do so via your Portal (leadpoint.energysmart.com.au) within the returns
                            period. Failure to adhere to this timescale will result in the enquiry being chargeable at
                            the full rate. We are unable to process returns via email or telephone - they must be
                            entered through the portal.</p>
                        </th>
                      </tr>
                      </tbody>
                    </table>
                  </th>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
          </tbody>
        </table>
      </center>
    </td>
  </tr>
  </tbody>
</table>
EOD;

  $mail->msgHTML($content);
  $emailSending = "";
  if (!$mail->Send()) {
    echo $emailSending = "Mailer Error: " . $mail->ErrorInfo;
    return FALSE;
  } else {
//    echo $emailSending = "Message sent!";
    return TRUE;
  }
}

// check source, return ID
function getCampaignID($source)
{
  $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($con->connect_errno) {
    printf("Connect failed: %s\n", $con->connect_error);
    exit();
  }
  $sql = "SELECT * FROM `campaigns` WHERE source='$source'";
  if ($result = $con->query($sql)) {
    $id = $result->fetch_assoc();
    $result->num_rows;
    if($result) {
      $con->close();
      return $id["id"];
    }
  }
  $con->close();
  return FALSE;
}

//phone number validation
function phone_valid($phone)
{
  $justNums = preg_replace("/[^0-9]/", '', $phone);
  return $justNums;
}

function postcodes_valid($postcodes)
{
  $justNums = preg_replace("/[^0-9,]/", '', $postcodes);
  return $justNums;
}


function prepareLeadInfo($p){
  $campaign_id = getCampaignID($p["source"]);
  $readyLeadInfo = array();
  $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($con->connect_errno) {
    printf("Connect failed: %s\n", $con->connect_error);
    exit();
  }

  // query to get fields from campaign
  $sql = 'SELECT lead_fields.id, lead_fields.key, lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
  $sql.= ' FROM `campaign_lead_fields_rel`';
  $sql.= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
  $sql.= ' WHERE campaign_lead_fields_rel.campaign_id ='.$campaign_id;
  $res = $con->query($sql);
  if ($res) {
    while( $res->fetch_assoc())
    {
      foreach ($res as $r) {
        $key = $r["key"];
        if($r["is_active"]){
          $preArray["key"] = $key;
          $preArray["val"] =  $p["$key"];
          $preArray["field_name"] = $r["name"];
          $readyLeadInfo[] = $preArray;
          unset($preArray);
        }
      }
    }
  } else {
    echo "<br>Something WRONG<br>";
    return FALSE;
  }
  return $readyLeadInfo;
}