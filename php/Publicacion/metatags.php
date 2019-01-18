<?php
/**
 * @Author: BrendaQuiroz
 * @Date:   2019-01-02 09:43:35
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-11 12:04:10
 */
 
function file_get_contents_curl($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
function getMeta($html){
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $metas = $doc->getElementsByTagName('meta');
    $ogArray=array();
    for ($i = 0; $i < $metas->length; $i++)
    {
        $meta = $metas->item($i);
        $propiedad=$meta->getAttribute("property");
        if($propiedad!=""){
            //echo "<pre>";
        	//echo $propiedad. ":" .$meta->getAttribute("content");
            //echo "</pre>";
            $ogArray[$propiedad]=($meta->getAttribute("content"));  
        }
    }
    $ogJson =  json_encode($ogArray);
    $ogJson64 = base64_encode($ogJson);
    if(!empty($ogArray) && isset($ogArray['og:url'])){
        return $ogJson64;
    }
    else
    {
        $link = "nometas";
        return $link;
    }
}
?>