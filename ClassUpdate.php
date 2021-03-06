<?php
//Secured input (UTF-8) into database after upgrading to PHP 5.6.23, causing problem with special characters - https://github.com/zongordon/CORS/issues/16

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Uppdatera t&auml;vlingsklass";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, uppdatera tävlingsklasser, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
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
// Update class data if button is clicked and all fields are validated to be correct
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "update_class")) {
    $class_fee = $_POST['class_fee'];	
    $class_weight_length = encodeToUtf8($_POST['class_weight_length']);
    $class_age = encodeToUtf8($_POST['class_age']);
    $output_form = 'no';
        
    if (empty($class_fee)) {
      // $class_fee is blank
      echo '<h1>Du gl&ouml;mde att fylla i avgift f&ouml;r klassen!</h1>';
      $output_form = 'yes';
    }
    else {
        if (!ctype_digit($class_fee)) {	
        // $class_fee input is not numeric
        echo '<h1>Bara siffror &auml;r till&aring;tet i f&auml;ltet f&ouml;r avgift!</h1>';
        $output_form = 'yes';
        }     
    }
 }
    else {  
    $output_form = 'yes';
    }
  	if ($output_form == 'yes') {

        $colname_rsClass = "1";
            if (isset($_GET['class_id'])) {
            $colname_rsClass = $_GET['class_id'];
            }
        //Select Class data
        mysql_select_db($database_DBconnection, $DBconnection);
        $query_rsClass = sprintf("SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, c.class_fee, co.comp_name FROM classes AS c JOIN competition AS co ON co.comp_id = c.comp_id WHERE class_id = %s", GetSQLValueString($colname_rsClass, "int"));
        $rsClass = mysql_query($query_rsClass, $DBconnection) or die(mysql_error());
        $row_rsClass = mysql_fetch_assoc($rsClass);
?>
        </div>
<h3>&Auml;ndra en t&auml;vlingsklass f&ouml;r att kunna anm&auml;la t&auml;vlande till</h3>
    <p>G&ouml;r &auml;ndringar i formul&auml;ret och klicka p&aring; knappen &quot;Uppdatera&quot;.</p>    
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="update_class" id="update_class">
        <table width="400" border="0">
          <tr>
            <td>T&auml;vling</td>
            <td><label>
<select name="comp_id" id="comp_id">
  <?php
do {  
?>
  <option value="<?php echo $row_rsClass['comp_id']?>"<?php if (!(strcmp($row_rsClass['comp_id'], $row_rsClass['comp_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['comp_name']?></option>
  <?php
} while ($row_rsClass = mysql_fetch_assoc($rsClass));
  $rows = mysql_num_rows($rsClass);
  if($rows > 0) {
      mysql_data_seek($rsClass, 0);
	  $row_rsClass = mysql_fetch_assoc($rsClass);
  }
?>
</select>
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lderskategori</td>
            <td><label>
              <select name="class_category" id="class_category">
                <option value="Senior" <?php if (!(strcmp("Senior", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Senior</option>
                <option value="U21" <?php if (!(strcmp("U21", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>U21</option>
                <option value="Junior" <?php if (!(strcmp("Junior", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Junior</option>
                <option value="Kadett" <?php if (!(strcmp("Kadett", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Kadett</option>
                <option value="Barn" <?php if (!(strcmp("Barn", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Barn</option>
                <?php
do {  
?>
                <option value="<?php echo $row_rsClass['class_category']?>"<?php if (!(strcmp($row_rsClass['class_category'], $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['class_category']?></option>
                <?php
} while ($row_rsClass = mysql_fetch_assoc($rsClass));
  $rows = mysql_num_rows($rsClass);
  if($rows > 0) {
      mysql_data_seek($rsClass, 0);
	  $row_rsClass = mysql_fetch_assoc($rsClass);
  }
?>
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Disciplin</td>
            <td valign="top"><p>
              <label>
<input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kata"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kata" id="class_discipline_0" />
Kata</label>
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kumite"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kumite" id="class_discipline_1" />
                Kumite</label>
              <br />
            </p></td>
          </tr>
          <tr>
            <td>T&auml;vlingsklass f&ouml;r (k&ouml;n)</td>
            <td valign="top"><p>
              <label>
                  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Man"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Man" id="class_gender_0" />
            Man</label>
              <label>
  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Kvinna"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Kvinna" id="class_gender_1" />
                Kvinna</label>
              <label>
  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Mix"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Mix" id="class_gender_2" />
                Mix</label>
              <br />
            </p></td>
          </tr>
          <tr>
            <td>K&ouml;nskategori</td>
<td valign="top"><label>
  <select name="class_gender_category" id="class_gender_category">
    <option value="Herrar" <?php if (!(strcmp("Herrar", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Herrar</option>
    <option value="Damer" <?php if (!(strcmp("Damer", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Damer</option>
    <option value="Pojkar" <?php if (!(strcmp("Pojkar", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Pojkar</option>
    <option value="Flickor" <?php if (!(strcmp("Flickor", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Flickor</option>
    <option value="Mix" <?php if (!(strcmp("Mix", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Mix</option>    
<?php
do {  
?>
    <option value="<?php echo $row_rsClass['class_gender_category']?>"<?php if (!(strcmp($row_rsClass['class_gender_category'], $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['class_gender_category']?></option>
<?php
} while ($row_rsClass = mysql_fetch_assoc($rsClass));
  $rows = mysql_num_rows($rsClass);
  if($rows > 0) {
      mysql_data_seek($rsClass, 0);
	  $row_rsClass = mysql_fetch_assoc($rsClass);
  }
?>
  </select>
</label></td>
          </tr>
          <tr>
            <td>Vikt- eller l&auml;ngdkategori</td>
            <td><label>
              <input name="class_weight_length" type="text" id="class_weight_length" value="<?php echo $row_rsClass['class_weight_length']; ?>" size="15" />
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lder eller namn p&aring; klass</td>
            <td><input name="class_age" type="text" id="class_age" value="<?php echo ltrim($row_rsClass['class_age']); ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="int" id="class_fee" value="<?php echo ltrim($row_rsClass['class_fee']); ?>" size="15" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_class" id="new_class" value="Uppdatera" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="update_class" />
        <input name="class_id" type="hidden" id="class_id" value="<?php echo $row_rsClass['class_id']; ?>" />
        <input type="hidden" name="MM_update" value="update_class" />
        <input type="hidden" name="MM_update" value="update_class" />
    </form>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsClass);
        }
 	else if ($output_form == 'no') {        
            if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update_class")) {
            $updateSQL = sprintf("UPDATE classes SET comp_id=%s, class_category=%s, class_discipline=%s, class_gender_category=%s, class_gender=%s, class_weight_length=%s, class_age=%s, class_fee=%s WHERE class_id=%s",
                       GetSQLValueString($_POST['comp_id'], "int"),
                       GetSQLValueString($_POST['class_category'], "text"),
                       GetSQLValueString($_POST['class_discipline'], "text"),
                       GetSQLValueString($_POST['class_gender_category'], "text"),
                       GetSQLValueString($_POST['class_gender'], "text"),
                       GetSQLValueString($class_weight_length, "text"),
                       GetSQLValueString($class_age, "text"),
                       GetSQLValueString($_POST['class_fee'], "int"),
                       GetSQLValueString($_POST['class_id'], "int"));

            mysql_select_db($database_DBconnection, $DBconnection);
            $Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());

            $updateGoTo = "ClassesList.php";
                if (isset($_SERVER['QUERY_STRING'])) {
                $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
                $updateGoTo .= $_SERVER['QUERY_STRING'];
                }
            header(sprintf("Location: %s", $updateGoTo));
            }
        }
ob_end_flush();
?>