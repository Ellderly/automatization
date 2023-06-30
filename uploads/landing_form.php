<?php 
    if (isset($_GET["utm_source"]))     echo "<input type=\"hidden\" name=\"utm_source\" value=\"{$_GET["utm_source"]};\" />";
    if (isset($_GET["utm_content"]))    echo "<input type=\"hidden\" name=\"utm_content\" value=\"{$_GET["utm_content"]}\" />";
    if (isset($_GET["utm_term"]))       echo "<input type=\"hidden\" name=\"utm_term\" value=\"{$_GET["utm_term"]}\" />";
    if (isset($_GET["utm_campaign"]))   echo "<input type=\"hidden\" name=\"utm_campaign\" value=\"{$_GET["utm_campaign"]}\" />";
    if (isset($_GET["utm_medium"]))     echo "<input type=\"hidden\" name=\"utm_medium\" value=\"{$_GET["utm_medium"]}\" />";
    if (isset($_GET["subid"]))          echo "<input type=\"hidden\" name=\"subid\" value=\"{$_GET["subid"]}\" />";
    if (isset($_GET['clickid']))        echo "<input type=\"hidden\" name=\"subid\" value=\"{$_GET['clickid']}\" />";
    if (isset($clickData['clickid']))   echo "<input type=\"hidden\" name=\"subid\" value=\"{$clickData['clickid']}\" />";
    if (isset($_GET["uuid"]))           echo "<input type=\"hidden\" name=\"uuid\" value=\"{$_GET["uuid"]}\" />";
    if (isset($_GET["fbclid"]))         echo "<input type=\"hidden\" name=\"fbclid\" value=\"{$_GET["fbclid"]}\" />";
    if (isset($_GET["gclid"]))          echo "<input type=\"hidden\" name=\"gclid\" value=\"{$_GET["gclid"]}\" />";
    if (isset($_GET["cpc"]))            echo "<input type=\"hidden\" name=\"cpc\" value=\"{$_GET["cpc"]}\" />";
    if (isset($_GET["cur"]))            echo "<input type=\"hidden\" name=\"cur\" value=\"{$_GET["cur"]}\" />";
?>