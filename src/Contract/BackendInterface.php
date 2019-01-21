<?php
namespace ParagonIE\CipherSweet\Contract;

use ParagonIE\CipherSweet\Backend\Key\SymmetricKey;

/**
 * Interface BackendInterface
 * @package ParagonIE\CipherSweet\Contract
 *
 * @method string getPrefix()
 */
interface BackendInterface
{
    /**
     * @param string $plaintext
     * @param SymmetricKey $key
     * @param string $aad       Additional authenticated data
     * @return string
     */
    public function encrypt($plaintext, SymmetricKey $key, $aad = '');

    /**
     * @param string $ciphertext
     * @param SymmetricKey $key
     * @param string $aad       Additional authenticated data
     * @return string
     */
    public function decrypt($ciphertext, SymmetricKey $key, $aad = '');

    /**
     * @param string $plaintext
     * @param SymmetricKey $key
     * @param int|null $bitLength
     *
     * @return string
     */
    public function blindIndexFast(
        $plaintext,
        SymmetricKey $key,
        $bitLength = null
    );

    /**
     * @param string $plaintext
     * @param SymmetricKey $key
     * @param int|null $bitLength
     * @param array $config
     *
     * @return string
     */
    public function blindIndexSlow(
        $plaintext,
        SymmetricKey $key,
        $bitLength = null,
        array $config = []
    );

    /**
     * @param string $tableName
     * @param string $fieldName
     * @param string $indexName
     * @return string
     */
    public function getIndexTypeColumn($tableName, $fieldName, $indexName);

    /**
     * @return string
     */
    // public function getPrefix();
}
