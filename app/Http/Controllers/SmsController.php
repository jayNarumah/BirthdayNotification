<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AWS;
use Exception;
use \Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use \Illuminate\Support\Facades\Log;
use \Aws\Credentials\Credentials;
use Twilio\Rest\Client;
        /** Aliasing the classes */

class SmsController extends Controller
{
    function twilio()
    {
        $receiver_number = "+2347066352444";
        $message = "Dear Imran A. Bala, This is to notify you that today is ".date('d F Y').". Happy Birthday, May you be blessed with a long, healthy life that brings you joy and happiness.";
        // Log::alert($message);

        try {

            $account_sid = getenv("TWILIO_SID");
            $account_token = getenv("TWILIO_TOKEN");
            $from = getenv("TWILIO_FROM");
           // Log::info($account_sid);

            $client = new Client($account_sid, $account_token);
            $client->messages->create($receiver_number, [
                'from' => $from,
                'body' => $message]);

            Log:Info('SMS Sent Successfully.');

        } catch (Exception $e) {
            Log::info("Error: ". $e->getMessage());
        }

    }
    function twilioSms()
    {
        $to = "+2347066352444";
        $from = getenv("TWILIO_FROM");
        $message = 'Hello from Twilio!';
        Log::alert(getenv("TWILIO_SID"));
        //open connection
        try {
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, getenv("TWILIO_SID").':'.getenv("TWILIO_TOKEN"));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/'.getenv("TWILIO_SID").'/Messages.json', getenv("TWILIO_SID")));
            curl_setopt($ch, CURLOPT_POST, 3);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'To='.$to.'&From='.$from.'&Body='.$message);

            // execute post
            $result = curl_exec($ch);
            $result = json_decode($result);

            // close connection
            curl_close($ch);

        } catch (\Throwable $th) {
            throw $th;
        }

        return $ch;
        //Sending message ends here
       // Log::alert($ch);
    }

    function twilioSMS1()
    {
        try {
            $account_sid = getenv("TWILIO_SID");
            $account_token = getenv("TWILIO_TOKEN");
            $from = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $account_token);

            $client->messages->create('+23466352444', [
                'from' => $from,
                'body' => 'Birthday Notification Test Sms!!!'
            ]);

            return "Message was Sent Successfully !!!";
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    function sendAwsMessage()
    {
        $access_key_id = env("AWS_ACCESS_KEY_ID");
        $access_key_token = env("AWS_SECRET_ACCESS_KEY");
        $credentials = new Credentials("AKIAX4WUFPBAWRJL3OP4", "DQuiB3NVCDHD9Op6aqsdfwaREzXEWAamCVRtEXWA");
        $sns = new SnsClient([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => $credentials
        ]);


        $args = array(
            'MessageAttributes' => [
        //]
            'AWS.SNS.SMS.SMSType' =>[
                'DataType' => 'String',
                'StringValue' => 'Transactional'
            ]
            ],

            'Message' => "BirthdayNotification test Sms!!!",
            'PhoneNumber' => "+2347035460599",
        );

        $result = $sns->publish($args);
        Log::alert($result);
    }
    function aws()
     {
        // $phone_number = "+2347066352444";
        // $sms = AWS::createClient('sns');

        // $sms->publish([
        //     'Message' => 'Hello, This is just a test Message from Century test BirthdayNotification Site',
        //     'PhoneNumber' => $phone_number,
        //     'MessageAttributes' => [
        //         'AWS.SNS.SMS.SMSType'  => [
        //             'DataType'    => 'String',
        //             'StringValue' => 'Transactional',
        //          ]
        //    ],
        // ]);
        // Log::alert($sms->publish());
     }

    function awsSms()
    {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL | E_STRICT);

        /** Make sure to add autoload.php */
        require __DIR__.'/vendor/autoload.php';
        /** Error Debugging */


        /** AWS SNS Access Key ID */
        $access_key_id    = 'XXX';

        /** AWS SNS Secret Access Key */
        $secret = 'XXX';

        /** Create SNS Client By Passing Credentials */
        $SnSclient = new SnsClient([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => 'AKIAX4WUFPBA7QEGUFV5',
                'secret' => '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',
            ],
        ]);

        /** Message data & Phone number that we want to send */
        $message = 'Testing AWS SNS Messaging';

        /** NOTE: Make sure to put the country code properly else SMS wont get delivered */
        $phone = '+2347035460599';

        try {
            /** Few setting that you should not forget */
            $result = $SnSclient->publish([
                'MessageAttributes' => array(
                    /** Pass the SENDERID here */
                    'AWS.SNS.SMS.SenderID' => array(
                        'DataType' => 'String',
                        'StringValue' => 'StackCoder'
                    ),
                    /** What kind of SMS you would like to deliver */
                    'AWS.SNS.SMS.SMSType' => array(
                        'DataType' => 'String',
                        'StringValue' => 'Transactional'
                    )
                ),
                /** Message and phone number you would like to deliver */
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);
            /** Dump the output for debugging */
            echo '<pre>';
            print_r($result);
            echo '<br>--------------------------------<br>';
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage() . "<br>";
        }

    }
}
