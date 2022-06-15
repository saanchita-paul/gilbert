<?php

namespace Origin\Commands;

use Illuminate\Console\Command;
use Origin\Services\StoreProductInfoAPI;

class OriginStorePlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'origin:store {campaign_id} {product_code}'
    ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch plans from origin and store to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $campaign_id = $this->argument('campaign_id');
        $product_code = $this->argument('product_code');

        $this->line('Origin fetch plan command started successfully!');
        try{
            $newStore = new StoreProductInfoAPI($campaign_id, $product_code);
            $saved = $newStore->fetch();
            if(!$saved){
                $this->error('Invalid inputs or product is not available');
            }
            else {
                $this->line('Origin Plan fetched and stored to database with id:');
                $this->line($saved['id']);
            }
        }
        catch (\Exception $e){
            $this->error($e->getMessage());
        }
        
        $this->line('Origin fetch plan lead command finished successfully!');
    }
}
