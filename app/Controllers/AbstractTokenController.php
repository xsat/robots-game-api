<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ForbiddenException;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\Player;
use MongoDB\Database;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractTokenController
 */
abstract class AbstractTokenController extends AbstractController
{
    /**
     * @var Player|null
     */
    private ?Player $player = null;

    /**
     * AbstractTokenController constructor.
     *
     * @param Request $request
     * @param Database $database
     *
     * @throws ForbiddenException
     */
    public function __construct(Request $request, Database $database)
    {
        parent::__construct($request, $database);

        $authorization = $request->headers->get('Authorization', '');

        if (preg_match('#^Bearer ([^ ]+)$#isU', $authorization, $matches)) {
            $playerToken = (new PlayerTokenMapper($database))->findByToken(
                $matches[1] ?? ''
            );

            if ($playerToken) {
                $this->player = (new PlayerMapper($database))->findByPlayerId(
                    $playerToken->getPlayerId()
                );
            }
        }

        if (!$this->player) {
            throw new ForbiddenException('Not Allowed.');
        }
    }

    /**
     * @return Player
     */
    protected function player(): Player
    {
        return $this->player;
    }
}
