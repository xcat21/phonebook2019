<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:08
 */

namespace App\Services;

use App\Models\Users;

/**
 * Business-logic for users
 *
 * Class UsersService
 */
class UsersService extends AbstractService
{
    /** Unable to create user */
    const ERROR_UNABLE_CREATE_USER = 11001;

    /** User not found */
    const ERROR_USER_NOT_FOUND = 11002;

    /** No such user */
    const ERROR_INCORRECT_USER = 11003;

    /** Unable to update user */
    const ERROR_UNABLE_UPDATE_USER = 11004;

    /** Unable to delete user */
    const ERROR_UNABLE_DELETE_USER = 1105;

    /** Key to store users list in cache */
    const CACHE_KEY = 'all_users';

    /**
     * Creating a new user
     *
     * @param array $userData
     */
    public function createUser(array $userData)
    {
        try {
            $user = new Users();
            $result = $user->setLogin($userData['login'])
                ->setPass(password_hash($userData['password'], PASSWORD_DEFAULT))
                ->setFirstName($userData['first_name'])
                ->setLastName($userData['last_name'])
                ->create();

            if (!$result) {
                throw new ServiceException('Unable to create user', self::ERROR_UNABLE_CREATE_USER);
            }

            // Adding user to cache if exists
            $cachedUsers = $this->cache->get(self::CACHE_KEY);
            if (!is_null($cachedUsers)) {
                $cachedUsers[$user->getId()] = $user->toArray();
                $this->cache->save(self::CACHE_KEY, $cachedUsers);
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23505) {
                throw new ServiceException('User already exists', self::ERROR_ALREADY_EXISTS, $e);
            } else {
                throw new ServiceException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    /**
     * Updating an existing user
     *
     * @param array $userData
     */
    public function updateUser(array $userData)
    {
        try {
            $user = Users::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $userData['id']
                    ]
                ]
            );

            $userData['login'] = (is_null($userData['login'])) ? $user->getLogin() : $userData['login'];
            $userData['password'] = (is_null($userData['password'])) ? $user->getPass() : password_hash($userData['password'], PASSWORD_DEFAULT);
            $userData['first_name'] = (is_null($userData['first_name'])) ? $user->getFirstName() : $userData['first_name'];
            $userData['last_name'] = (is_null($userData['last_name'])) ? $user->getLastName() : $userData['last_name'];

            $result = $user->setLogin($userData['login'])
                ->setPass($userData['password'])
                ->setFirstName($userData['first_name'])
                ->setLastName($userData['last_name'])
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update user', self::ERROR_UNABLE_UPDATE_USER);
            }

            // Updating user in cache if exists
            $cachedUsers = $this->cache->get(self::CACHE_KEY);
            if (!is_null($cachedUsers) && isset($cachedUsers[$userData['id']])) {
                $cachedUsers[$userData['id']] = $user->toArray();
                $this->cache->save(self::CACHE_KEY, $cachedUsers);
            }
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete an existing user
     *
     * @param int $userId
     */
    public function deleteUser($userId)
    {
        try {
            $user = Users::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $userId
                    ]
                ]
            );

            if (!$user) {
                throw new ServiceException("User not found", self::ERROR_USER_NOT_FOUND);
            }

            $result = $user->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete user', self::ERROR_UNABLE_DELETE_USER);
            }

            // Deleting from cache if exists
            $cachedUsers = $this->cache->get(self::CACHE_KEY);
            if (!is_null($cachedUsers) && isset($cachedUsers[$userId])) {
                unset($cachedUsers[$userId]);
                $this->cache->save(self::CACHE_KEY, $cachedUsers);
            }
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns user list
     *
     * @return array
     */
    public function getUserList()
    {
        try {
            $cachedUsers = $this->cache->get(self::CACHE_KEY);

            if (is_null($cachedUsers)) {

                $users = Users::find(
                    [
                        'conditions' => '',
                        'bind'       => [],
                        'columns'    => "id, login, first_name, last_name",
                    ]
                );

                if (!$users) {
                    $this->cache->save(self::CACHE_KEY, []);
                    return [];
                }

                $usersResult = $users->toArray();
                $cachedUsers = array_combine(array_column( $usersResult, 'id'), $usersResult );

                $this->cache->save(self::CACHE_KEY, $cachedUsers);
            }

            return $cachedUsers;
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
