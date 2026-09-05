<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//echo "<pre>";print_r($_POST);die;
$servername = "localhost";
$username = "u361785735_payrollsystem";
$password = "Vantage360!!!";

try {
    $conn = new PDO("mysql:host=$servername;dbname=".$username, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $data['success'] = true;
    $data['message'] = 'Error Please contact at contact@caisol.com';
}
$sth = $conn->prepare("SELECT * FROM settings");
$sth->execute();
$data = $sth->fetchAll(PDO::FETCH_ASSOC);

if(!empty($data))
{
    foreach ($data as $k=>$v)
    {
        $email = !empty($v['notification_email'])?$v['notification_email']:'hassanphp7@gmail.com';
        $payroll_cycle = !empty($v['payroll_cycle'])?$v['payroll_cycle']:0;
        //echo date('t').'----'.date('d');
        if(date('t') == date('d')){
            echo 'Last day of the month.';
        }
        $current_day_of_month = date('d');
        if($payroll_cycle==1)
        {
            if(in_array($current_day_of_month,array(7,14,21,28)))
            {
                sendNotification($email,$payroll_cycle);
            }
        }
        elseif($payroll_cycle==2)
        {
            if(in_array($current_day_of_month,array(14,28)))
            {
                sendNotification($email,$payroll_cycle);
            }
        }
        elseif($payroll_cycle==3)
        {
            $last_day_of_month = date('t');

            if($last_day_of_month==$current_day_of_month)
            {
                sendNotification($email,$payroll_cycle);
            }
        }
        else
        {
            sendNotification($email,$payroll_cycle);
        }
    }
}
$invoiceQuery = $conn->prepare("SELECT i.*,t.email as customer_email FROM invoices i left join tenants t on t.id=i.customer_id where paidcheck=0 and date(invoice_due_date) BETWEEN NOW() AND (NOW() + INTERVAL 7 day)  ");
$invoiceQuery->execute();
$invoiceData = $invoiceQuery->fetchAll(PDO::FETCH_ASSOC);
if(!empty($invoiceData))
{
    foreach ($invoiceData as $key=>$val)
    {
        $contents = curl_get_contents("https://payrollsystem.caisol.com/invoice/send-email-cron/".$val['id']);
    }
}
die;
function sendNotification($email='',$cycle=1)
{
    $cyclePayroll=[
        '1'=>'Weekly',
        '2'=>'Biweekly',
        '3'=>'Monthly',
        '4'=>'Other',
    ];
    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/";
    $cycle_name = !empty($cyclePayroll[$cycle])?$cyclePayroll[$cycle]:'Daily';
    // Configure your Subject Prefix and Recipient here
    $subjectPrefix = 'Contact via PayrollSystem';
    $emailTo       = $email;
//$emailTo       = 'hassanphp7@gmail.com';
    $errors = array(); // array to hold validation errors
    $data   = array(); // array to pass back data

    $subject="Payroll Cycle ".$cycle_name;
    $subject = "$subjectPrefix $subject";
    $message = 'Please run  <a href="'.$actual_link.'payroll" target="_blank">Payroll</a> <br>';
    $body    = '<html>

    <head>
        <title>Payroll Cycle Notification</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
            <strong>Hi: </strong>'.$emailTo.'<br />
            <strong>Message: </strong>'.nl2br($message).'<br />

            </body>
</html>
        ';
    $headers="";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//$headers  = "MIME-Version: 1.1" . PHP_EOL;
//$headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
    $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
    $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
    $headers .= "Message-ID: <" . "info" . '@' . "caisol.com" . '>' . PHP_EOL;
    $headers .= "From: " . "=?UTF-8?B?".base64_encode("Caisol")."?=" . "<info@caisol.com>" . PHP_EOL;
    $headers .= "Return-Path: $emailTo" . PHP_EOL;
    $headers .= "Reply-To: $email" . PHP_EOL;
    $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
    $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
    mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", ($body), $headers);
    mail("hassanphp7@gmail.com", "=?utf-8?B?" . base64_encode($subject) . "?=", ($body), $headers);
    $data['success'] = true;
    $data['message'] = $message;
    $conn = null;
    // return all our data to an AJAX call
    echo json_encode($data);
}

function sendInvoiceNotification($email='',$val)
{

    // Configure your Subject Prefix and Recipient here
    $subjectPrefix = 'Contact via PayrollSystem';
    $emailTo       = $email;
//$emailTo       = 'hassanphp7@gmail.com';
    $errors = array(); // array to hold validation errors
    $data   = array(); // array to pass back data

    $subject="Invoice ".$val['invoice_id']." Due Date ".$val['invoice_due_date'];
    $subject = "$subjectPrefix $subject";
    $message = 'Please review Invoice number # '.$val['invoice_id'].' has due date of '.$val['invoice_due_date'].'   <br>';
    $body    = '<html>

    <head>
        <title>Invoice # '.$val['invoice_id'].' Due Date '.$val['invoice_due_date'].' Notification</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
            <strong>Hi: </strong>'.$emailTo.'<br />
            <strong>Message: </strong>'.nl2br($message).'<br />

            </body>
</html>
        ';
    $headers="";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//$headers  = "MIME-Version: 1.1" . PHP_EOL;
//$headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
    $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
    $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
    $headers .= "Message-ID: <" . "info" . '@' . "caisol.com" . '>' . PHP_EOL;
    $headers .= "From: " . "=?UTF-8?B?".base64_encode("Caisol")."?=" . "<info@caisol.com>" . PHP_EOL;
    $headers .= "Return-Path: $emailTo" . PHP_EOL;
    $headers .= "Reply-To: $email" . PHP_EOL;
    $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
    $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
    mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", ($body), $headers);
    mail("hassanphp7@gmail.com", "=?utf-8?B?" . base64_encode($subject) . "?=", ($body), $headers);
    $data['success'] = true;
    $data['message'] = $message;
    $conn = null;
    // return all our data to an AJAX call
    echo json_encode($data);
}

function curl_get_contents($url)
{
    try{
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);
    }
    catch (Exception $exception)
    {

    }
}


function d($params=array(),$die=false)
{
    echo "<pre>";
    print_r($params);
    if($die)
    {
        die();
    }
}
