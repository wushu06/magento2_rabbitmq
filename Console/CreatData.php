<?php
namespace Elementary\Queue\Console;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreatProduct
 * @package Elementary\Queue\Console
 */
class CreatData extends Command
{
    /**
     * @var JsonHelper
     */
    private $jsonHelper;
    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $publisher;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\File\Csv
     */
    private $csv;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * CreatProduct constructor.
     * @param \Magento\Framework\MessageQueue\PublisherInterface $publisher
     * @param \Magento\Framework\File\Csv $csv
     * @param \Magento\Framework\Filesystem $filesystem
     * @param null $name
     */
    public function __construct(
        \Magento\Framework\MessageQueue\PublisherInterface $publisher,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        $name = null
    ) {
        $this->publisher = $publisher;
        parent::__construct($name);
        $this->csv = $csv;
        $this->filesystem = $filesystem;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('wam:sync:data');
        $this->setDescription('WAM sync data');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this|int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Order export started</info>');
        try {
            $path =  $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath();
            $path .= 'test.csv';
            $this->import($path);
            $output->writeln('<info>Success</info>');
        } catch (\Exception $e) {
            $output->writeln('<info>Failed</info>');
        }
        return $this;
    }

    /**
     * @param $file
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function import($file)
    {
        if (!isset($file)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }
        $csvData = $this->csv->getData($file);
        foreach ($csvData as $row => $data) {
            if ($row > 0) {
                $this->publisher->publish('elementary.create.data', json_encode($data));
            }
        }
        die();
    }
}
