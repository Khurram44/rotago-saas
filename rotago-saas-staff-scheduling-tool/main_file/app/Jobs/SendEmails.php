<?php

namespace App\Jobs;

use App\Models\User;
use App\Rotas;
use App\User as AppUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $has_users = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($has_users)
    {
        $this->has_users = $has_users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        if(!empty($this->has_users)) 
        {
            foreach($this->has_users as $user)
            {
                $rotas_data = Rotas::all()->toArray();                
                \Mail::send('email.sendmaintrest', ['rotas_data' => $rotas_data], function ($m) use($rotas_data) {
                            $m->to('henis@rajodiya.com');
                            $m->subject('Details of your rotas for this week.');
                        });                
            }
        }

        // foreach ($subscribers as $subscriber)
        // {
        //     \Mail::send('email.sendmaintrest', ['post' => $this->post, 'subscriber' => $subscriber], function ($m) use($subscriber) {
        //         $m->to('henis@rajodiya.com', $subscriber['first_name']);
        //         $m->subject('A new article has been published.');
        //     });
        // }
    }
}
