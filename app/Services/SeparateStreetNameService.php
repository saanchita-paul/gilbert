<?php


namespace App\Services;


use App\Models\ConnectionApplication;
use App\Services\Address\StreetTypeMapper;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class SeparateStreetNameService
{

    /**
     * @var $leads ConnectionApplication
     */
    private $leads;

    private $failingLeads = [];

    private $successedStreetMappedLeads = [];

    private function getOldLeads()
    {
        $this->leads = ConnectionApplication::query()
            ->whereNull(['street_name_only', 'street_type'])
            ->whereNotNull(['street_name'])
            ->get();
    }

    private function updateStreet()
    {
        $progressBar = $this->getProgress($this->leads->count());
        $progressBar->start();

        foreach ($this->leads as $lead)
        {
            $progressBar->advance();

            $fullStreetName = $this->separateStreetName($lead->street_name);
            $lead->street_name_only = $fullStreetName['street_name_only'];
            $lead->street_type = $fullStreetName['street_type'];

            if(empty($lead->street_name_only) || empty($lead->street_type))
            {
                $this->failingLeads[] = ['connection_id' =>$lead->id , 'street_name' =>  $lead->street_name];
                continue;
            }
            $this->successedStreetMappedLeads[] = $lead->id;
            $lead->save();
        }

        $progressBar->finish();
    }

    private function separateStreetName($street_name): array
    {
        $separateStreet = explode(' ', $street_name);
        $size = sizeof($separateStreet);
        if($size  === 1) {
            return ['street_name_only'=> $separateStreet[0], 'street_type'=> null];
        }

        $streetTypeMapper = new StreetTypeMapper();
        $givenStreetType = trim($separateStreet[$size - 1]);
        $streetType = $streetTypeMapper::getShortForm($givenStreetType);

        if(empty($streetType) && $fullForm = $streetTypeMapper::getFullForm($givenStreetType)) {
            $streetType = strtoupper($givenStreetType);
        }

        array_pop( $separateStreet);
        $streetName = implode(' ', $separateStreet);
        return ['street_name_only'=> $streetName, 'street_type'=> $streetType];
    }


    public function updateOldData()
    {
        $this->getOldLeads();
        $this->updateStreet();

        $result = ['failingLeads'=> $this->failingLeads, 'successesStreetMappedLeads'=> $this->successedStreetMappedLeads];

        info('mapped old street name ', $result);

        return $result;
    }

    private function getProgress($items = 0): ProgressBar
    {
        $out = new ConsoleOutput();
        $out->writeln('<info>Separating street name and type</info>');
        $p = new ProgressBar($out, $items);
        $p->setFormat('<comment>%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%  %memory:6s%</comment>');

        return $p;
    }


}
