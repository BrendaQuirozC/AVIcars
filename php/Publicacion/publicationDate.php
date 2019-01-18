<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-11-15 11:11:36
 * @Last Modified by:   Brenda Quiroz
 * @Last Modified time: 2018-11-15 13:32:06
 */

function hace($fecha){
$diferencia = time() - $fecha ;
$segundos = $diferencia ;
$minutos = round($diferencia / 60 );
$horas = round($diferencia / 3600 );
$dias = round($diferencia / 86400 );
$semanas = round($diferencia / 604800 );
$mes = round($diferencia / 2419200 );
$anio = round($diferencia / 29030400 );

if($segundos <= 60){
echo "hace segundos";

}else if($minutos <=60){
if($minutos==1){
echo "hace un minuto";
}else{
echo "hace $minutos minutos";
}
}else if($horas <=24){
if($horas==1){
echo "hace una hora";
}else{
echo "hace $horas horas";
}
}else if($dias <= 7){
if($dias==1){
echo "hace un dia";
}else{
echo "hace $dias dias";
}
}else if($semanas <= 4){
if($semanas==1){
echo "hace una semana";
}else{
echo "hace $semanas semanas";
}
}else if($mes <=12){
if($mes==1){
echo "hace un mes";
}else{
echo "hace $mes meses";
}
}else{
if($anio==1){
echo "hace un a&ntilde;o";
}else{
echo "hace $anio a&ntilde;os";
}
}
}  