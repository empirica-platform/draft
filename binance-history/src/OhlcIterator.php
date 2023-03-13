<?php

namespace EmpiricaPlatform\BinanceHistory;

use EmpiricaPlatform\BinanceHistory\ValueObject\Ohlc;
use EmpiricaPlatform\BinanceHistory\ValueObject\Price;
use EmpiricaPlatform\BinanceHistory\ValueObject\Volume;
use EmpiricaPlatform\Contracts\OhlcInterface;
use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\Contracts\SymbolPairInterface;
use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use ReturnTypeWillChange;
use SplFileObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use wapmorgan\UnifiedArchive\Exceptions\ArchiveExtractionException;
use wapmorgan\UnifiedArchive\UnifiedArchive;

class OhlcIterator extends SplFileObject implements OhlcIteratorInterface
{
    /**
     * @param SymbolPairInterface $symbolPair
     * @param string $sourceFile
     * @param string $workDir
     * @param string $sourceType
     * @throws ArchiveExtractionException
     * @throws GuzzleException
     */
    // SymbolPair, timeFrame, timeFrom, timeTo
    public function __construct(
        protected SymbolPairInterface $symbolPair,
        string $sourceFile,
        string $workDir,
        string $sourceType = 'url'
    )
    {
        $zipFile = $workDir . parse_url($sourceFile, PHP_URL_PATH);
        $csvFile = Path::changeExtension($zipFile, 'csv');
        if ($sourceType === 'url' && !file_exists($zipFile)) {
            $filesystem = new Filesystem();
            $filesystem->mkdir(dirname($zipFile));
            $client = new Client();
            $request = new Request('GET', $sourceFile);
            $client->send($request, ['sink' => fopen($zipFile, 'wb+')]);
        }
        if (in_array($sourceType, ['url', 'zip'], true) && !file_exists($csvFile)) {
            $zip = UnifiedArchive::open($zipFile);
            $zip->extract(dirname($zipFile));
        }

        parent::__construct($csvFile);
        $this->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
    }

    protected const HEADER = 'open_time,open,high,low,close,volume,close_time,quote_volume,count,taker_buy_volume,taker_buy_quote_volume,ignore';

    #[ReturnTypeWillChange] public function current(): OhlcInterface
    {
        try {
            [$openTime, $penPrice, $highPrice, $lowPrice, $closePrice, $volume] = parent::current();
            $U = substr($openTime, 0, -3);
            $v = substr($openTime, -3, 3);
            $dateTime = DateTimeImmutable::createFromFormat('U.v', "$U.$v");
            $open = new Price($penPrice);
            $high = new Price($highPrice);
            $low = new Price($lowPrice);
            $close = new Price($closePrice);
            $volume = new Volume($volume);

            return new Ohlc($this->symbolPair, $dateTime, $open, $high, $low, $close, $volume);
        } catch (\Throwable $e) {
            $lineNum = $this->key() + 1;
            throw new \RuntimeException("Error in $lineNum line of csv file", 0, $e);
        }
    }

    // not need count(), runtime ready
    public function count(): int
    {
        $count = 0;
        while (!$this->eof()) {
            $this->next();
            $count++;
        }

        return $count;
    }
}
