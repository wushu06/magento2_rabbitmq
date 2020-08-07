<?php
namespace Elementary\Queue\Model\Data;

class Consumer
{
    /**
     * @var \Zend\Log\Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $logFileName = '-data.log';

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;
    private $connection;

    /**
     * DeleteConsumer constructor.
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->directoryList = $directoryList;
        $logDir = $directoryList->getPath('log');
        $date = date('m-d-Y-h:i:s', time());
        $writer = new \Zend\Log\Writer\Stream($logDir . DIRECTORY_SEPARATOR . $date . $this->logFileName);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->connection= $this->resourceConnection->getConnection();
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    public function processMessage(string $product)
    {
        $themeTable = $this->resourceConnection->getTableName('search_synonyms');
        $product = json_decode($product);
        if ($product && !empty($product) && $product[1] !== '') {
            $sql = "INSERT INTO " . $themeTable . "(synonyms) VALUES ('" . $product[1] . "')";
            $this->connection->query($sql);
            $this->logger->info($product);
        }
    }
}
