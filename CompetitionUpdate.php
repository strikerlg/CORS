<?php
//Secured input (UTF-8) into database after upgrading to PHP 5.6.23, causing problem with special characters - https://github.com/zongordon/CORS/issues/16

ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="&Auml;ndra t&auml;vling";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, ändra tävling, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//Get competitons id from previous page
$colname_rsCompetition = "-1";
if (isset($_GET['comp_id'])) {
  $colname_rsCompetition = $_GET['comp_id'];
}
//Select all columns from the selected competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetition = sprintf("SELECT * FROM competition WHERE comp_id = %s", GetSQLValueString($colname_rsCompetition, "int"));
$rsCompetition = mysql_query($query_rsCompetition, $DBconnection) or die(mysql_error());
$row_rsCompetition = mysql_fetch_assoc($rsCompetition);
$totalRows_rsCompetition = mysql_num_rows($rsCompetition);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class ="feature">    
        <div class="error">
<?php
 //Validate the form if button is clicked        
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "CompForm")) {
    $comp_name = encodeToUtf8($_POST['comp_name']);
    $comp_start_date = $_POST['comp_start_date'];
    $comp_end_date = $_POST['comp_end_date'];
    $comp_end_reg_date = $_POST['comp_end_reg_date'];
    $comp_max_regs = $_POST['comp_max_regs'];
    $comp_current = $_POST['comp_current'];
    $output_form = 'no';

    if (empty($comp_name)) {
      // $comp_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens namn!</h3>';
      $output_form = 'yes';
    }
    if (empty($comp_start_date)) {
      // $comp_start_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens startdatum!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_start_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_start_date)) {
    // $comp_start_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; t&auml;vlingens startdatum!</h3>';
    $output_form = 'yes';
    }	        
    if (empty($comp_end_date)) {
      // $comp_end_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens slutdatum!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_end_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_end_date)) {
    // $comp_end_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; t&auml;vlingens slutdatum!</h3>';
    $output_form = 'yes';
    }	            
    if (empty($comp_end_reg_date)) {
      // $comp_end_reg_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens sista anm&auml;lningsdag!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_end_reg_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_end_reg_date)) {
    // $comp_end_reg_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; sista anm&auml;lningsdag!</h3>';
    $output_form = 'yes';
    }	            
    if (empty($comp_max_regs)) {
      // $comp_max_regs is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens maximala antal anm&auml;lningar!</h3>';
      $output_form = 'yes';
    }    
    if (!empty($comp_max_regs ) && !is_numeric($comp_max_regs)) {
    // $comp_max_regs is not a number
    echo '<h3>Du anv&auml;nde annat format &auml;n siffror f&ouml;r max antal anm&auml;lningar!</h3>';
    $output_form = 'yes';
    }	                    
} 

else {
    $output_form = 'yes';
}

if ($output_form == 'yes') {
?>          
        </div>
<h3>&Auml;ndra &ouml;nskade v&auml;rden och klicka p&aring; &quot;Spara&quot; f&ouml;r att spara och g&aring; tillbaka till listan &ouml;ver t&auml;vlingar.  </h3>
  </div>
  <div class="story">
    <form id="CompForm" name="CompForm" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="400" border="0">
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">T&auml;vlingens namn:</td>
          <td>&nbsp;</td>
          <td><input name="comp_name" type="text" value="<?php echo $row_rsCompetition['comp_name']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Startdatum:</td>
          <td>&nbsp;</td>
          <td><input name="comp_start_date" type="text" value="<?php echo $row_rsCompetition['comp_start_date']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Slutdatum:</td>
          <td>&nbsp;</td>
          <td><input name="comp_end_date" type="text" value="<?php echo $row_rsCompetition['comp_end_date']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Sista anm&auml;lningsdag:</td>
          <td>&nbsp;</td>
          <td><input name="comp_end_reg_date" type="text" value="<?php echo $row_rsCompetition['comp_end_reg_date']; ?>" size="32" /></td>
        </tr>
        <tr>
            <td align="right" valign="baseline" nowrap="nowrap">Max antal anm&auml;lningar:</td>
            <td>&nbsp;</td> 
            <td><label>
            <input name="comp_max_regs" type="text" id="comp_max_regs" value="<?php echo $row_rsCompetition['comp_max_regs']; ?>"/>              
            </label></td>
        </tr>        
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Aktiv:</td>
          <td>&nbsp;</td>
          <td><label>
          <input type="checkbox" name="comp_current" id="comp_current" 
          <?php if (!(strcmp($row_rsCompetition['comp_current'],1))) {
                        //Disable checkbox if competition is current (active)
                   echo "checked=\"checked\" disabled='disabled'/ />(&auml;ndrar du i listan &ouml;ver t&auml;vlingar)";
                   echo "<input name='comp_current' type='hidden' value='true'/>";
                }   
                else {
                   echo "/>"; 
                } ?>  
          </label></td>
        </tr>
        <tr>
<td align="right" valign="baseline" nowrap="nowrap"><input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompetition['comp_id']; ?>" /></td>
          <td>&nbsp;</td>
          <td><input name="CompUpdate" type="submit" id="CompUpdate" value="Spara" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="CompForm" />
    </form>
    <p>&nbsp;</p>
<?php    
} 
//Save the competition information
else if ($output_form == 'no') {

        //If button is clicked for updating then update to columns from data in the form
        if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "CompForm")) {
                // Set all competitions first to non-current (0) if the new competition shall be current
                if ($_POST["comp_current"] == "on") {
                $resetSQL = sprintf("UPDATE competition SET comp_current = 0");
                mysql_select_db($database_DBconnection, $DBconnection);
                $Result1 = mysql_query($resetSQL, $DBconnection) or die(mysql_error());
                }
        // Update all competition data          
        $updateSQL = sprintf("UPDATE competition SET comp_name=%s, comp_start_date=%s, comp_end_date=%s, comp_end_reg_date=%s, comp_max_regs=%s, comp_current=%s WHERE comp_id=%s",
                       GetSQLValueString($comp_name, "text"),
                       GetSQLValueString($_POST['comp_start_date'], "date"),
                       GetSQLValueString($_POST['comp_end_date'], "date"),
                       GetSQLValueString($_POST['comp_end_reg_date'], "date"),
                       GetSQLValueString($_POST['comp_max_regs'], "int"),
                       GetSQLValueString(isset($_POST['comp_current']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comp_id'], "int"));

            mysql_select_db($database_DBconnection, $DBconnection);
        $Result2 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
            //After update redirect to page listing the competitions
            $updateGoTo = "CompetitionList.php?" . $row_rsCompetition['comp_id'] . "=" . $row_rsCompetition['comp_id'] . "";
            if (isset($_SERVER['QUERY_STRING'])) {
            $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
            $updateGoTo .= $_SERVER['QUERY_STRING'];
            }
        header(sprintf("Location: %s", $updateGoTo));
        }
}
?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsCompetition);
ob_end_flush();
?>