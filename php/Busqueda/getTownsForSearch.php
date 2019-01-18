<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-05 17:09:33
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-05 17:31:36
 */

require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
$country=new Country;
$towns=$country->getTownsByState($_POST["state"]);
echo '<option value="0" selected>Cualquiera</option>';
foreach ($towns as $t => $town) {
	echo '<option value="'.$town.'">'.$town.'</option>';
}