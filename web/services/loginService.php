<?php
/*
 * Copyright (C) 2009 Igalia, S.L. <info@igalia.com>
 *
 * This file is part of PhpReport.
 *
 * PhpReport is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhpReport is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhpReport.  If not, see <http://www.gnu.org/licenses/>.
 */

/** login web service.
 *
 * @filesource
 * @package PhpReport
 * @subpackage services
 * @author Jorge López Fernández
 */

    define('PHPREPORT_ROOT', __DIR__ . '/../../');
    include_once(PHPREPORT_ROOT . '/web/services/WebServicesFunctions.php');
    include_once(PHPREPORT_ROOT . '/model/facade/UsersFacade.php');
    include_once(PHPREPORT_ROOT . '/model/vo/UserVO.php');

    /* Allow login only via HTTP Authentication data only if both username and password are not empty*/
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        header('WWW-Authenticate: Basic realm="phpeport Authentication"');
        header('HTTP/1.0 401 Unauthorized');
        http_response_code(401);
        $userLogin = false;
        $userPassword = false;
    } else {
        $userLogin = $_SERVER['PHP_AUTH_USER'];
        $userPassword = $_SERVER['PHP_AUTH_PW'];
    }

    $string = "";

    try{

        $user = UsersFacade::Login($userLogin, $userPassword);

        session_start();

        $_SESSION['user'] = $user;

        $sessionId = session_id();

        $string = $string . "<login><sessionId>$sessionId</sessionId></login>";

    }
    catch(IncorrectLoginException $exc){

    $string = $string . "<login><error id='1'>" . $exc->getMessage() . "</error></login>";

    }

   // make it into a proper XML document with header etc
    $xml = simplexml_load_string($string);

   // send an XML mime header
    header("Content-type: text/xml");

   // output correctly formatted XML
    echo $xml->asXML();
