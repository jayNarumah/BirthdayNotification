<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;
use App\Models\Profile;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayAlertMail;
use App\Mail\BirthdayMail;
use App\Models\GroupMember;
use App\Models\Group;
use Exception;

class DairlyBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the Email and Sms for today birthdays';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $day = date("d");
        $month = date("m");
        $like = "%-".$month."-" . $day;

        $account_sid = getenv("TWILIO_SID");
        $account_token = getenv("TWILIO_TOKEN");
        $from = getenv("TWILIO_FROM");

        //Checking for the Profiles whose birthdays are Today
        $profiles = Profile::where('dob', 'like', $like)->get();

        foreach($profiles as $profile)  //Iteration through to obtain their data
        {
            $details = [
                'name' => $profile->name,
                'email' => $profile->email,
                'dob' => $profile->dob,
                'phone_number' => $profile->phone_number,
                '_name' => '',
                'birthday' => date('d') .' ' . date('M'),
                'group_name' => '',
            ];

            // Log::Info($profile->id . " Hello ". $profile->name ." Your Receiving this Message  becouse Your Birthday is Today");
            Mail::to($profile->email)->queue(new BirthdayMail($details)); //Sending Birthday Message to the user

            $receiver_number = $details['phone_number'];
            $receiver_number = "+2347066352444";
            $message = "Dear ".$details['name'].", This is to notify you that today is ".date('d F Y').". Happy Birthday, May you be blessed with a long, healthy life that brings you joy and happiness.";

            try {

                $client = new Client($account_sid, $account_token);
                $client->messages->create($receiver_number, [
                    'from' => $from,
                    'body' => $message]);

                Log:Info('SMS Sent Successfully.');

            } catch (Exception $e) {
                $this->info("Error: ". $e->getMessage());
            }

            // exit();
            //getting his record that consist the groups he is in
            $user_groups = GroupMember::where('profile_id', $profile->id)->get();

            foreach ($user_groups as $user_group) //Iterating through to get multiple groups he belongs
            {
                if($user_group->profile_id == $profile->id) //recheck the selected user if he is the targeted user
                {
                    $group = Group::findOrFail($user_group->group_id); //getting his Group
                    $group_members = GroupMember::where('group_id', $group->id)->get(); //getting Co-members of the Group

                    $details['group_name'] = $group->group_name;

                    foreach ($group_members as $group_member)
                    {
                        if($group_member->profile_id != $user_group->profile_id)
                        {
                            //iterating through to send the birthday message

                        if ($group_member->profile_id != $profile->id) //Checking to see if his not the person having birthday
                        {
                        $member = Profile::findOrFail($group_member->profile_id);
                        $details['_name'] = $member->name;

                         Mail::to($profile->email)->queue(new BirthdayAlertMail($details)); //Sending Birthday Message to the user
                        //  exit();
                        //  Log::Info($member->id . " Hello " .$member->name." You are receiving this Message becouse Your Group Member ". $profile->name." on ". $group->group_name ."is celebrating birthday Today");
                        }
                        }
                    }

                }
            }
        }

    $this->info('Hourly Update has been send successfully');
    }
}
