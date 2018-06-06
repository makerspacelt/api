<?php

namespace App\Controller\V1;

use App\Service\JsonDB;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;

/**
 * @NamePrefix("api_v1_")
 */
class UserController extends FOSRestController
{
    /**
     * @Get("/users/allowed/{cardId}/{toolMac}")
     *
     * @param JsonDB $DB
     * @param string $cardId
     * @param string $toolMac
     *
     * @return View
     */
    public function getUserAllowedAction(JsonDB $DB, $cardId, $toolMac)
    {
        $user = $DB->getUserByCardId($cardId);
        $tool = $DB->getToolByMac($toolMac);

        if (! $user || !$tool) {
            return $this->createError("Bad tool/user provided");
        }

        if (! $user['user']['active']) {
            return $this->createError("User is not active");
        }

        if (! $user['user']['cards'][$cardId]['active']) {
            return $this->createError("User card is not active");
        }

        return View::create([
            "status" => in_array($tool['key'], $user['user']['tools']),
            "ttl" => (int)getenv("API_TTL")
        ]);
    }

    /**
     * Construct error response
     *
     * @param $msg
     *
     * @return View
     */
    private function createError($msg)
    {
        return View::create(
            [
                "status" => false,
                "ttl" => (int)getenv("API_TTL"),
                "error" => $msg
            ]
        );
    }
}
