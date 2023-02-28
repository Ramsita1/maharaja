<?php

function SendEmail($emailTo = '', $emailSubject = '', $emailBody = '', array $attachments = [], $emailFromName = '', $emailFromEmail = '', $email_cc = '', $email_bcc = '')
{  

    $whitelist = array(
        '127.0.0.1',
        '::1',
        'localhost'
    );

    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        return;
    }

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: Infiway <noreply@infiway.ae>' . "\r\n";
    //$emailBody=CleanHtml($emailBody);
    return mail($emailTo,$emailSubject,$emailBody,$headers);
    return false;

    \Mail::send(
        'Email.Index', 
        ['html' => $emailBody], 
        function($message) 
        use($emailTo, $emailSubject, $attachments, $emailFromName, $emailFromEmail, $email_cc, $email_bcc) 
        {
            $message->from(
                ($emailFromEmail?$emailFromEmail:'noreply@infiway.ae'), 
                ($emailFromName?$emailFromName:'Infiway')
            )->subject($emailSubject);

            $message->to(explode(',', $emailTo));

            if ($email_cc) {
                $message->cc(explode(',', $email_cc));
            }
            if ($email_bcc) {
                $message->cc(explode(',', $email_bcc));
            }
            if (empty($attachments) &&  is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    $message->attach($attachment);
                }
            }        
        }
    );
}

function inspectionpdf($email, $customer_name, $customer_address, $event_start_time= '', $start_date, $first_name){
        
    //PDF::SetCreator(PDF_CREATOR);
    PDF::SetAuthor('Bond CRM');
    PDF::SetTitle("Bond CRM ".$customer_name);
    PDF::SetSubject("Bond CRM ".$customer_name);
    PDF::SetKeywords("Bond CRM ".$customer_name);
    PDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    PDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    PDF::SetMargins(PDF_MARGIN_LEFT, 1, PDF_MARGIN_RIGHT);
    PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);
   
    $data['date'] = $start_date; 
    $data['time'] = $event_start_time;
    $data['address']  = $customer_address;
    $data['repname'] = $first_name;
    $html = view("PDF/inspectionPDF",$data);

    PDF::setFontSubsetting(true);
    PDF::SetPrintFooter(false);
    PDF::SetFont('times', '', 16,'','false');
    PDF::AddPage();
    PDF::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $path = public_path("public/images/uploads/pdf/".date('Y')."/".date('m'));
    File::makeDirectory($path, $mode = 0777, true, true);
    $file_name= $path."/inspectionpdf-".date('YmdThis').".pdf";    
    PDF::Output($file_name, 'F');
    return $file_name;
}