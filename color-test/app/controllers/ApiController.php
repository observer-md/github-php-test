<?php

require_once ("BaseController.php");
require_once (DIR_APP . '/models/VoteModel.php');

/**
 * API controller
 */
class ApiController extends BaseController
{
    /**
     * Init
     */
    public function __construct()
    {
        // check API access
    }

    /**
     * API Get color votes
     * @link http://test.com/?c=api&a=get-votes&id=1
     */
    public function actionGetVotes()
    {
        return self::renderJson([
            'success' => true,
            'data'    => VoteModel::init()->getVotesByColor($_GET['id'] ?? 0),
        ]);
    }
}
