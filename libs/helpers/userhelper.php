<?php

/**
 * @package     Synapse
 * @subpackage  Helpers/User
 */

defined('_INIT') or die;


class UserHelper
{
    /**
     * Checks if the users with the specified username and password exists and sets the session
     * @param String $username
     * @param String $password
     * @return bool|mixed
     * @throws Error
     */
    public static function login($username, $password)
    {
        $db = App::getDBO();

        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__users')
            ->where('username = '.$db->quote($username))
            ->where('password = '. $db->quote(md5($password)));

        $user = $db->setQuery($query)->loadObject();

        if($user->id){
            App::getSession()->set('user', $user)->set('isLoggedin', true);
            return $user;
        }

        return false;
    }

    /**
     * Logout the user by destroying the session data
     */
    public static function logout()
    {
        App::getSession()->remove('user')->remove('isLoggedin');
    }

    /**
     * Return one or more users based on the selected field
     * @param String $field
     * @param String $value
     * @return bool|mixed
     */
    public static function getByField($field, $value)
    {
        $db = App::getDBO();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from('#__users')
            ->where($field.' = '.$db->quote($value));
        $db->setQuery($query);
        $user = $db->loadObjectList();

        return $user;
    }

    public function update($user)
    {
        $db = App::getDBO();

        if(!$db->updateObject('#__users', $user, 'id', true)){
            return false;
        }

        return $user;
    }
}