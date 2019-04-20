<?php
namespace ParagonIE\CipherSweet\KeyProvider;

use ParagonIE\CipherSweet\Backend\Key\SymmetricKey;
use ParagonIE\CipherSweet\Contract\BackendInterface;
use ParagonIE\CipherSweet\Contract\KeyProviderInterface;
use ParagonIE\CipherSweet\Exception\ArrayKeyException;
use ParagonIE\CipherSweet\Exception\CryptoOperationException;
use ParagonIE\CipherSweet\Util;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\ConstantTime\Binary;
use ParagonIE\ConstantTime\Hex;

/**
 * Class ArrayProvider
 * @package ParagonIE\CipherSweet\KeyProvider
 */
class ArrayProvider implements KeyProviderInterface
{
    const INDEX_SYMMETRIC_KEY = 'symmetric-key';

    /**
     * @var BackendInterface
     */
    private $backend;

    /**
     * @var string
     */
    private $rootSymmetricKey;

    /**
     * ArrayProvider constructor.
     *
     * @param BackendInterface $backend
     * @param array<string, string> $config
     *
     * @throws ArrayKeyException
     * @throws CryptoOperationException
     */
    public function __construct(BackendInterface $backend, array $config = [])
    {
        if (!isset($config[self::INDEX_SYMMETRIC_KEY])) {
            throw new ArrayKeyException(
                'Expected key "' .
                    self::INDEX_SYMMETRIC_KEY .
                    '" to be defined on array.'
            );
        }
        $this->backend = $backend;

        /** @var string $rawKey */
        $rawKey = $config[self::INDEX_SYMMETRIC_KEY];
        if (Binary::safeStrlen($rawKey) === 64) {
            $this->rootSymmetricKey = Hex::decode($rawKey);
        } elseif (Binary::safeStrlen($rawKey) === 44) {
            $this->rootSymmetricKey = Base64UrlSafe::decode($rawKey);
        } elseif (Binary::safeStrlen($rawKey) === 32) {
            $this->rootSymmetricKey = $rawKey;
        } else {
            throw new CryptoOperationException('Invalid key size');
        }
    }

    /**
     * Attempt to wipe memory.
     *
     * @throws \SodiumException
     */
    public function __destruct()
    {
        Util::memzero($this->rootSymmetricKey);
    }

    /**
     * @return BackendInterface
     */
    public function getBackend()
    {
        return $this->backend;
    }

    /**
     * @return SymmetricKey
     */
    public function getSymmetricKey()
    {
        return new SymmetricKey($this->rootSymmetricKey);
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [];
    }
}
