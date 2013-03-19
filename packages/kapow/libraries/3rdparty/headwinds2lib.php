<?php
// Copyright (c) Tien Le, Thai Bui, Wu-chang Feng 2007-2011
// Portland State University
define('PRIVATE_KEY','');
define('API_KEY','');
define('INTERVAL', 60*5); //period where two keys can exist at the same time.
define('REFRESHING_TIME', 24 * 3600);

function initialize_kapow( $location,$formID=null)
{
    $html = '<script src="http://rabbit.cs.pdx.edu/headwinds/application/js/kapow.js"></script>';
    if ($formID===null)
    {
        $html.= '<input type="hidden" name="kapowField" id="kapowField" value="" />';
        $html.= "<script> kapowInitialize('$location');</script>";
    }
    else
        $html.= "<script>kapowInitialize('$location','$formID');</script>";
    return $html;
}


function kapow_verify($kapowField)
{
    try
    {
        //extract data from object
        $kapow =  json_decode($kapowField);
        $Data =$kapow->comment.$kapow->author.$kapow->email;
        $Adone = $kapow->Adone;
        $S = $kapow->S;
        $ts = $kapow->ts;

        //verify
        $Ks_array = generateKsArray();

        //use $Ks to verify the answer
        foreach($Ks_array as $Ks)
        {
            $total = $Data.$Ks.$S.$ts;
            $Cookie = hash('md5', $total);
            $final = $Ks.$Cookie;
            if(md5($final) == $Adone)
            {
                return 1;
            }
        }
    }
    catch (Exception $e)
    {
        return false;
    }
    return 0;
}

/*
 * Verify if user actually solve the puzzle before posting
 *  @param $Adone Hash used for verification
 *  @param $Data  Contains information of user's post
 *  @param $S     Local score
 *  @param $ts    timestamp
 *  @return       1 if user actually solve the puzzle, 0 otherwise
 */
function _headwinds2_verify_hash_cookie($Adone, $Data, $S, $ts){
    //if submittion happens in transition period, two keys can exist at the same time.
    $Ks_array = generateKsArray();
    //use $Ks to verify the answer
    foreach($Ks_array as $Ks)
    {
        $total = $Data.$Ks.$S.$ts;
        $Cookie = hash('md5', $total);
        $final = $Ks.$Cookie;
        if(md5($final) == $Adone){
            return 1;
        }
    }
    return 0;
}

function _headwinds2_return_initial_cookie($msg, $author, $email){

    $Ks = getKsFromPK(PRIVATE_KEY);
    $ip = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
    $S = 10; // Local score
    $ts = date("m.d.y h:i:s");
    $Data = $msg.$author.$email;

    $total = $Data.$Ks.$S.$ts;
    $Cookie = hash('md5', $total);
    return array(
        'Cookie' =>  $Cookie,
        'ts' => $ts,
        'S'     => $S,
        'comment_author' => $author,
        'comment_author_email' => $email,
        'comment_author_ip' => $ip,
        'comment_date' => $ts,
        'comment_author_url' => 'http://google.com',
        'comment_content' => $msg,
        'Data'  => $Data,
        'api_key' => API_KEY
    );
}

/*
* Generate new Ks from the private key. If generation happens at the beginning of a period, two Ks are created.
* @param  $private_key
* @return  $Ks_array array contains key(s)
*/
function generateKsArray()
{
    $dateTime = new DateTime("now");
    $ts = $dateTime->getTimestamp();
    if (intval($ts%REFRESHING_TIME) < INTERVAL)
        $Ks_array = array ( getKsFromPK(PRIVATE_KEY),  getKsFromPK(PRIVATE_KEY,true));
    else
        $Ks_array = array ( getKsFromPK(PRIVATE_KEY));
    return $Ks_array;
}

/*
 * Generate new Ks from the private key. The generated key is different for each period indicated by REFRESHING_TIME
 * It can also be used to generated Ks from the previous period by setting the $previous param to true
 * @param  $private_key
 * @param  $previous: set to true if wanting to generate key of previous period
 * @return  Ks
 */
function getKsFromPK($private_key, $previous = false)
{
    if ($previous == true)
        $k = 1;
    else
        $k = 0;
    $dateTime = new DateTime("now");
    $ts = $dateTime->getTimestamp();
    $concat = (intval( $ts /REFRESHING_TIME) - $k). $private_key ;
    return hash ('sha256', $concat);
    //return $concat;
}
