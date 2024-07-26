<?php
namespace App\Service;

use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\RSA;

class SftpUploader
{
    private $sftp;
    private $privateKey;

    public function __construct(string $host, int $port, string $username, string $privateKeyPath)
    {
        $this->sftp = new SFTP($host, $port);
        $this->privateKey = RSA::load(file_get_contents($privateKeyPath));
        $this->sftp->login($username, $this->privateKey);
    }

    public function uploadFile(string $localFilePath, string $remoteFilePath)
    {
        $this->sftp->put($remoteFilePath, file_get_contents($localFilePath));
    }
}
