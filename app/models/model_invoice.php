<?php
include'app/libs/tfpdf/tfpdf.php';
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 16:52
 */
class Model_Invoice extends Model
{
    private $pdf;
    public function __construct()
    {
        $this->pdf=new tFPDF();
    }
    public function getMyInvoices($id)
{
    $dir=$_SERVER['DOCUMENT_ROOT'].'/docs/invoices/'.$id.'/';
    $arr=array();
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            $arr[]=$file;
        }
        if ($arr) return $arr;
        return false;
    }
}
    private function getData()
{
    $con=$this->db();
    $client=$_POST['client'];
    $startOfPeriod=date(strtotime('monday previous week'));
    $sql="SELECT ler.full_name, led.lead_id, cli.lead_cost, cli.campaign_name, cli.email, cli.phone from clients cli left join leads_delivery led on cli.id=led.client_id left join leads_lead_fields_rel ler on led.lead_id=ler.id where cli.id='".$client."'";
    //return $sql;
    //$sql.="and led.timedate>'".$startOfPeriod."'";
    $res=$con->query($sql);
    //return $sql;
    if($res) {
        $result = $res->fetch_all();
        return $result;
    }
    return false;
}
    public function generate()
    {
        $data=$this->getData();
        //return var_dump($data);
        $userId=$_POST['client'];
        $today=date('d_m_Y');
        $company_name=$data[0][3];
        $company_phone=$data[0][5];
        $company_email=$data[0][4];
        $invoice_number='45779';
        $invoice_date=date('d M Y');
        $total_due='$1,500.00';
        $terms='7 days from issue';
        $subtotal='3,477.00';
        $payment='Payment information here';
        $gst='243.39';
        $discount='243.39';
        $totalDue='1,500.00';
        $termsAndCond='Terms and conditions here';


        $this->pdf->AddPage('','Letter');
        $this->pdf->SetAutoPageBreak(auto);
        $this->pdf->Image('app/libs/tfpdf/template/logo1.png', 13, 11, '65', '15','png');
        $this->pdf->SetFont('Helvetica','',9);
        $this->pdf->SetDrawColor(213, 90, 36);
        $this->pdf->SetFillColor(213, 90, 36);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetXY(135,12);
        $this->pdf->MultiCell(70, 4, '395 Nepean Highway, Frankston, 3199, VIC
Phone: 1300 850 117
E-mail: support@energysmart.com.au', '','L');

        $this->pdf->SetLineWidth(0.1);
        $this->pdf->SetDrawColor(50, 186, 217);
        $this->pdf->Line(10, 30, 205, 30);

        $this->pdf->SetFont('Times','B',36);
        $this->pdf->setTextColor(50, 186, 217);
        $this->pdf->SetXY(135,47);
        $this->pdf->MultiCell(70, 4, 'INVOICE', '','L');

        $this->pdf->SetFont('Helvetica','B',13);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(12,55);
        $this->pdf->MultiCell(40, 4, 'INVOICE TO:', '','L');

        //Here will be company name
        $this->pdf->SetFont('Helvetica','B',11);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(12,65);
        $this->pdf->MultiCell(100, 4, $company_name, '','L');

        //Here will be company's phone
        $this->pdf->Image('app/libs/tfpdf/template/ico_phone.png', 13, 71, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(18,71);
        $this->pdf->MultiCell(60, 4, $company_phone, '','L');

        //Here will be company's email
        $this->pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, 77, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(18,77);
        $this->pdf->MultiCell(80, 4, $company_email, '','L');

        //Here will be invoice number
        $this->pdf->Image('app/libs/tfpdf/template/ico_nr.png', 137, 65, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(141,65);
        $this->pdf->MultiCell(40, 4, 'invoice No:', '','L');

        $this->pdf->SetXY(165,65);
        $this->pdf->MultiCell(40, 4, $invoice_number, '','L');

        //Here will be invoice Date
        $this->pdf->Image('app/libs/tfpdf/template/ico_nr.png', 137, 71, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(141,71);
        $this->pdf->MultiCell(40, 4, 'invoice Date:', '','L');

        $this->pdf->SetXY(165,71);
        $this->pdf->MultiCell(40, 4, $invoice_date, '','L');

        //Here will be Total Due
        $this->pdf->Image('app/libs/tfpdf/template/ico_price.png', 137, 77, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(141,77);
        $this->pdf->MultiCell(40, 4, 'Total Due:', '','L');

        $this->pdf->SetXY(165,77);
        $this->pdf->MultiCell(40, 4, $total_due, '','L');

        //Header of Leads Table
        $this->pdf->SetFont('Helvetica','B',10);
        $this->pdf->setTextColor(255, 255, 255);
        $this->pdf->SetXY(10,100);
        $this->pdf->SetFillColor(143, 145, 147);
        $this->pdf->MultiCell(97, 8, '   Lead Name', '','L',true);
        $this->pdf->SetXY(107,100);
        $this->pdf->MultiCell(33, 8, 'Lead Number', '','C',true);
        $this->pdf->SetXY(140,100);
        $this->pdf->MultiCell(30, 8, 'Lead Price', '','C',true);
        $this->pdf->SetFillColor(50, 186, 217);
        $this->pdf->SetXY(170,100);
        $this->pdf->MultiCell(35, 8, 'Total', '','C',true);

        //Here will be added all leads in cycle
        $this->pdf->setTextColor(0, 0, 0);
        $text1='weqgf qwerf';
        $text2='weqgf qwerf';
        $text3='weqgf qwerf';
        $text4='weqgf qwerf';
        $vertical=109;
        for ($i=0;$i<count($data);$i++)
        {
            $this->pdf->SetFillColor(240, 241, 241);
            $this->pdf->SetXY(10,$vertical);
            $this->pdf->SetFont('Helvetica','B',10);
            $this->pdf->MultiCell(97, 5, '   '.$data[$i][0], '','L',true);
            $this->pdf->SetXY(107,$vertical);
            $this->pdf->SetFont('Helvetica','',10);
            $this->pdf->MultiCell(33, 5, $data[$i][1], '','C',true);
            $this->pdf->SetXY(140,$vertical);
            $this->pdf->MultiCell(30, 5, $data[$i][2], '','C',true);
            $this->pdf->SetFillColor(234, 246, 249);
            $this->pdf->SetXY(170,$vertical);
            $this->pdf->MultiCell(35, 5, ($data[$i][2]*1.1), '','C',true);
            $vertical+=6;
            if($i==22 || ($i-22)%40==0) {
                $vertical=10;
                $this->pdf->AddPage('','Letter');
            }
        }

        //Here will be Terms
        //Here will be company's email
        $vertical+=20;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }

        //Here Will be terms
        $this->pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, $vertical, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(18,$vertical);
        $this->pdf->MultiCell(80, 4, 'Terms: '.$terms, '','L');

        $subtotal=($data[0][2]*($i+1));
        $gst=((($data[0][2]*1.1)-$data[0][2])*($i+1));
        $totaldue=$subtotal+$gst;

        //Here will be Subtotal
        $this->pdf->SetXY(140,$vertical);
        $this->pdf->MultiCell(30, 4, 'Subtotal: ', '','R');

        $this->pdf->SetXY(178,$vertical);
        $this->pdf->MultiCell(60, 4, '$ '.$subtotal, '','L');

        $vertical+=10;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }

        //GST %
        $this->pdf->SetXY(140,$vertical);
        $this->pdf->MultiCell(30, 4, 'GST %: ', '','R');

        $this->pdf->SetXY(178,$vertical);
        $this->pdf->MultiCell(60, 4, '$ '.$gst, '','L');

        $vertical+=20;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }

        //Here Will be terms
        $this->pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, $vertical, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(18,$vertical);
        $this->pdf->MultiCell(80, 4, 'Payment Information: '.$payment, '','L');

        $this->pdf->SetXY(140,$vertical);
        $this->pdf->MultiCell(30, 4, 'Discount 0%: ', '','R');

        $this->pdf->SetXY(178,$vertical);
        $this->pdf->MultiCell(60, 4, '$ '.$discount, '','L');

        $vertical+=10;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }

        //Total Due
        $this->pdf->SetFont('Helvetica','B',10);
        $this->pdf->setTextColor(255, 255, 255);
        $this->pdf->SetXY(10,100);
        $this->pdf->SetFillColor(143, 145, 147);

        $this->pdf->SetXY(140,$vertical);
        $this->pdf->MultiCell(30, 8, 'Total Due: ', '','R',true);

        $this->pdf->SetFillColor(50, 186, 217);
        $this->pdf->SetXY(170,$vertical);
        $this->pdf->MultiCell(35, 8, '$ '.$totaldue, '','C',true);

        $vertical+=20;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }

        $this->pdf->SetFillColor(50, 186, 217);
        $this->pdf->SetXY(170,$vertical);
        $this->pdf->MultiCell(35, 8, '  Pay Now', '','C',true);
        $this->pdf->Image('app/libs/tfpdf/template/ico_paynow.png', 172, 1+$vertical, '6', '6','png');

        $vertical+=20;
        if($vertical>=253)
        {
            $this->pdf->AddPage('','Letter');
            $vertical=10;
        }
        //Here Will be Terms & Conditions
        $this->pdf->Image('app/libs/tfpdf/template/ico_terms.png', 13, $vertical, '4', '4','png');
        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(0, 0, 0);
        $this->pdf->SetXY(18,$vertical);
        $this->pdf->MultiCell(80, 4, 'Terms & Conditions: '.$termsAndCond, '','L');

        $this->pdf->SetFont('Helvetica','',10);
        $this->pdf->setTextColor(255, 255, 255);
        $this->pdf->SetFillColor(143, 145, 147);
        $this->pdf->SetXY(10,259);
        $this->pdf->MultiCell(65, 10, 'support@energysmart.com.au  ', '','R',true);
        $this->pdf->SetXY(75,259);
        $this->pdf->MultiCell(65, 10, '   1300 850 117', '','C',true);
        $this->pdf->SetXY(140,259);
        $this->pdf->MultiCell(65, 10, 'www.energysmart.com.au   ', '','R',true);
        $this->pdf->Image('app/libs/tfpdf/template/footer_mail.png', 18, 261, '6', '6','png');
        $this->pdf->Image('app/libs/tfpdf/template/footer_phone.png', 90, 261, '6', '6','png');
        $this->pdf->Image('app/libs/tfpdf/template/footer_web.png', 152, 261, '6', '6','png');
        $dir=$_SERVER['DOCUMENT_ROOT'].'/docs/invoices/'.$userId;
        if(!is_dir ($dir))
        {
            mkdir($dir, 0777);
        }
            return $this->pdf->output($dir."/".$today.".pdf","F");
    }
}