<?php

namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JsonDB
{
    private $dbPath;
    private $db;

    public function __construct(string $dbPath)
    {
        $this->dbPath = $dbPath;
        $this->db = json_decode(file_get_contents($dbPath), true);
    }

    /**
     * Fetch user by provided card id
     *
     * @param string $reqCardId
     *
     * @return array|null
     */
    public function getUserByCardId(string $reqCardId)
    {
        foreach ($this->db['users'] as $name => $user) {
            foreach ($user['cards'] as $cardId => $card) {
                if($cardId == $reqCardId) {
                    return ['key' => $name, 'user' => $user];
                }
            }
        }

        return null;
    }

    /**
     * Get tool by its mac address
     *
     * @param string $mac
     *
     * @return array|null
     */
    public function getToolByMac(string $mac)
    {
        foreach ($this->db['tools'] as $key => $tool) {
            if ($tool['mac'] == $mac) {
                return ['key' => $key, 'tool' => $tool];
            }
        }

        return null;
    }
}
