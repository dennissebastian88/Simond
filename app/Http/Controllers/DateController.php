<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Date as Date;
use App\Models\User as User;
use Illuminate\Support\Facades\Config;

class DateController extends Controller
{
    protected $user, $date;
    protected $config, $city, $gender, $sexual_preference, $personality_type, $age, $date_status;

    public function __construct(User $user, Date $date) {
        $this->user = $user;
        $this->date = $date;

        $this->config   = Config::get('constants');
        $this->gender   = $this->config['gender'];
        $this->date_status  = $this->config['date_status'];
        $this->city = $this->config['city'];
        $this->sexual_preference = $this->config['sexual_preference'];
        $this->personality_type = $this->config['personality_type'];
        $this->age = $this->config['age'];
    }

    /*
     * Make date request
     */
    public function requestDate(Request $request) {
        $receiver   = $request->id;
        $user       = session()->get('user');

        if ($this->activeDateExists($user, $receiver)) {
            return redirect()->back()->with('flash_error', 'You have an active date/request with this user');
        }

        $date       = new Date();
        $date->sender_id = $user->id;
        $date->receiver_id = $receiver;
        $date->status = 0;
        $date->payment_status = 0;
        $date->sender_confirmation = 0;
        $date->receiver_confirmation = 0;

        $date->save();
        $this->updateUserInfo($user);

        return redirect()->back()->with('flash_success', 'Date request has been successfully sent!');
    }


    /*
     * Returns view of sent dates
     */
    public function viewSent() {
        $user   = session()->get('user');
        $dates  = $this->date->where('sender_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('sent')->with('dates', $dates)
            ->with('gender', $this->gender)
            ->with('status', $this->date_status);
    }


    /*
     * Returns view of received dates
     */
    public function viewReceived() {
        $user   = session()->get('user');
        $dates  = $this->date->where('receiver_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('received')->with('dates', $dates)
            ->with('gender', $this->gender)
            ->with('status', $this->date_status);
    }


    /*
     * Returns view of date
     */
    public function viewDate(Request $request) {
        $date = $this->date->find($request->id);
        $user = session()->get('user');

        $target = new User();
        if ($user->id == $date->sender->id) {
            $target = $date->receiver;
        } else {
            $target = $date->sender;
        }

        return view('date')->with('date', $date)
            ->with('user', $user)
            ->with('target', $target)
            ->with('data', [
                'config' => $this->config,
                'city' => $this->city,
                'gender' => $this->gender,
                'sexual_preference' => $this->sexual_preference,
                'personality_type' => $this->personality_type,
                'age' => $this->age
            ]);
    }


    /*
     * Update date status
     */
    public function update(Request $request) {
        $status = $request->status;
        $date   = $this->date->find($request->id);

        $date->status = $status;
        $date->save();

        return redirect()->back()->with('flash_success', 'Date request status has been updated!');
    }


    /*
     * Check if active date exists
     */
    private function activeDateExists($user, $receiver) {
        $sent       = $this->date->where('sender_id', $user->id)->where('receiver_id', $receiver)->get();
        $received   = $this->date->where('receiver_id', $user->id)->where('sender_id', $receiver)->get();

        if ($sent) {
            foreach ($sent as $s) {
                if ($s->status < 2) {
                    return true;
                }
            }
        }

        if ($received) {
            foreach ($received as $s) {
                if ($s->status < 2) {
                    return true;
                }
            }
        }

        return false;
    }


    /*
     * replace user info in session with new one
     */
    private function updateUserInfo($user) {
        $user   = $this->user->find($user->id);
        session()->put('user', $user);
    }
}
