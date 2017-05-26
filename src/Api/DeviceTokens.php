<?php

namespace Tomloprod\IonicApi\Api;

/**
 * Class DeviceTokens
 *
 * Stores ionic push api methods related to device tokens collection.
 * More info: https://docs.ionic.io/api/endpoints/push.html
 *
 * @package Tomloprod\IonicApi\Api
 * @author Tomás L.R (@tomloprod)
 * @author Ramon Carreras (@ramoncarreras)
 */
class DeviceTokens extends Request {

    private static $endPoints = [
        'list' => '/push/tokens', // GET
        'create' => '/push/tokens', // POST
        'retrieve' => '/push/tokens/:token_id', // GET
        'update' => '/push/tokens/:token_id', // PATCH
        'delete' => '/push/tokens/:token_id', // DELETE,
        'listAssociatedUsers' =>'/push/tokens/:token_id/users', // GET
        'associateUser' =>'/push/tokens/:token_id/users/:user_id', // POST
        'dissociateUser' =>'/push/tokens/:token_id/users/:user_id', // DELETE
    ];

    /**
     * Paginated listing of tokens.
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#get-tokens Ionic documentation
     * @param array $parameters
     * @return object $response
     */
    public function paginatedList($parameters = []) {
        return $this->sendRequest(
            self::METHOD_GET,
            self::$endPoints['list'] . '?' . http_build_query($parameters)
        );
    }


    /**
     * Saves a device token that was previously generated by a device platform.
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#post-tokens Ionic documentation
     * @param array $parameters
     * @return object $response
     */
    public function create($parameters) {
        return $this->sendRequest(
            self::METHOD_POST,
            self::$endPoints['create'],
            $parameters
        );
    }

    /**
     * Get information about a specific Token.
     * @link https://docs.ionic.io/api/endpoints/push.html#get-tokens-token_id Ionic documentation
     * @param string $deviceToken - Device token
     * @return object $response
     */
    public function retrieve($deviceToken) {
        return $this->prepareRequest(
            self::METHOD_GET,
            $deviceToken,
            self::$endPoints['retrieve']
        );
    }

    /**
     * Updates a token.
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#patch-tokens-token_id Ionic documentation
     * @param string $deviceToken - Device token
     * @param array $parameters
     * @return object $response
     */
    public function update($deviceToken, $parameters) {
        return $this->prepareRequest(
            self::METHOD_PATCH,
            $deviceToken,
            self::$endPoints['update'] . '?' . http_build_query($parameters)
        );
    }

    /**
     * Delete a device related to the device token.
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#delete-tokens-token_id Ionic documentation
     * @param string $deviceToken - Device token
     * @return object $response
     */
    public function delete($deviceToken) {
        return $this->prepareRequest(
            self::METHOD_DELETE,
            $deviceToken,
            self::$endPoints['delete']
        );
    }

    /**
     * List users associated with the indicated token
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#get-tokens-token_id-users Ionic documentation
     * @param string $deviceToken - Device token
	 * @param array $parameters - Query parameters (pagination).
     * @return object $response
     */
    public function listAssociatedUsers($deviceToken, $parameters = []) {
        return $this->prepareRequest(
            self::METHOD_GET,
            $deviceToken,
            self::$endPoints['listAssociatedUsers'] . '?' .  http_build_query($parameters)
        );
    }

    /**
     * Associate the indicated user with the indicated device token
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#post-tokens-token_id-users-user_id Ionic documentation
     * @param string $deviceToken - Device token
     * @param string $userId - User id
     * @return object $response
     */
    public function associateUser($deviceToken, $userId) {
        // Replace :user_id by $userId
        $endPoint = str_replace(':user_id', $userId, self::$endPoints['associateUser']);
        return $this->prepareRequest(
            self::METHOD_POST,
            $deviceToken,
            $endPoint
        );
    }

    /**
     * Dissociate the indicated user with the indicated device token
     *
     * @link https://docs.ionic.io/api/endpoints/push.html#delete-tokens-token_id-users-user_id Ionic documentation
     * @param string $deviceToken - Device token
     * @param string $userId - User id
     * @return object $response
     */
    public function dissociateUser($deviceToken, $userId) {
        // Replace :user_id by $userId
        $endPoint = str_replace(':user_id', $userId, self::$endPoints['dissociateUser']);
        return $this->prepareRequest(
            self::METHOD_DELETE,
            $deviceToken,
            $endPoint
        );
    }

    /**
     * Replace device token by token id —md5 of device token— and send to the Ionic Push API.
     *
     * @private
     * @param string $method
     * @param string $deviceToken
     * @param string $endPoint
     * @return object $response
     */
    private function prepareRequest($method, $deviceToken, $endPoint) {
        $tokenId = md5($deviceToken);
        return $this->sendRequest(
            $method,
            str_replace(':token_id', $tokenId, $endPoint)
        );
    }

}
