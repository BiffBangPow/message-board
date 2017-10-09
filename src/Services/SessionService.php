<?php

namespace BiffBangPow\MessageBoard\Services;

class SessionService
{
    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $_SESSION['isLoggedIn'];
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $_SESSION['userId'] = $userId;
    }

    /**
     * @return mixed
     */
    public function getIsLoggedIn()
    {
        return $_SESSION['userId'];
    }

    /**
     * @param mixed $isLoggedIn
     */
    public function setIsLoggedIn($isLoggedIn)
    {

        $_SESSION['isLoggedIn'] = $isLoggedIn;
    }




}