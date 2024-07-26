<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Console\Input\InputOption;

class FetchDataCommand extends Command
{
    protected static $defaultName = 'app:fetch-data';
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetch data from the API and save it as JSON')
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'Date for the data to fetch', date('Ymd'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = $input->getOption('date');
        $response = $this->httpClient->request('GET', 'https://dummyjson.com/users');
        $data = $response->getContent();

        $filename = sprintf('data_%s.json', $date);
        file_put_contents($filename, $data);

        $output->writeln('Data fetched and saved as ' . $filename);

        return Command::SUCCESS;
    }
}
