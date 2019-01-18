<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2019-01-02 09:43:35
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified ti me: 2019-01-11 12:35:11
 */

$string = "This a #hashtag #ahora   and this is a mention @erikfer94@gmail.com  and this is an email@mail.com @natalie, this does not exist @pats";

echo getMentions($string);

function getMentions($string) {
    require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicacion.php';
    require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
    $coder = new Coder();
    $Publicacion = new Publicacion;
    $mentionRegex = '/(?<!\w)@[@.\ñ\Ña-zA-Z0-9_-]+/';
    $hashtagRegex = '/#[\ñ\Ña-zA-Z0-9]+/';
    preg_match_all($mentionRegex,$string,$mentionMatches);
    preg_match_all($hashtagRegex,$string,$hashtagMatches);
    $mentionKeywords = array();
    $mention_replace = array();
    $hashtagKeywords = array();
    $hashtag_replace = array();
    foreach ($mentionMatches[0] as $match){
        $usrname = "";
        $lenmatch = strlen($match);
        for ($i=1; $i < $lenmatch ; $i++) //Tomar el username sin el @ 
        { 
            $usrname .= $match[$i];
        }
        $iduser = $Publicacion->idMention($usrname);
        if($iduser){
            $mentionKeywords []= ($match);
            $idcoded = $coder->encode($iduser);
            $mention_replace[] =  (' <a target="_blank" href="/perfil/?cuenta='.$idcoded.'"> '.$match.'</a> ');
        }
    }
    foreach ($hashtagMatches[0] as $hmatch) {
        $hashname = "%23";
        $idPublication = $Publicacion->idHashtag($hmatch);
        $lenhmatch = count($idPublication);
        for ($i=1; $i < strlen($hmatch) ; $i++) //Tomar el username sin el @ 
        { 
            $hashname .= $hmatch[$i];
        }
        if($lenhmatch > 0){
            $hashtagKeywords []= ($hmatch);
            $hashtag_replace[] =  (' <a href="/hashtag/?src='.$hashname.'"> '.$hmatch.'</a> ');
        }
    }

    $replaceMention = str_replace($mentionKeywords, $mention_replace, $string);
    $replaceHashtag = str_replace($hashtagKeywords, $hashtag_replace, $replaceMention);
    
    return $replaceHashtag;
}