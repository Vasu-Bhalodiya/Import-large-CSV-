<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Cron Job running at ". now());

        $data['name']='Vasu';
        $data['email']='xyz'.time().'@gmail.com';
        $data['password']='1234567890';
        $data['created_at']=now()->format('Y-m-d H:i:s');
        $data['updated_at']=now()->format('Y-m-d H:i:s');
        DB::table('users')->insert($data);
        info('success');
        

        // info('Command runs every minute.');
    }
}
