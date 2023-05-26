<?php
include_once(SINIF."db.php");
$DB = new DB();
$settings = $DB->getData("settings", "WHERE id=?", array(1), "ORDER BY id ASC", 1);
if ($settings != false)
{
    $sitetitle = $settings[0]["title"];
    $sitekey = $settings[0]["key"];
    $sitedescription = $settings[0]["description"];
    $siteURL = $settings[0]["url"];
}
?>