<?php
putenv('ORACLE_HOME=/oraclient');
        $dbh=ocilogon('a0105595','crse1420','(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL = TCP)(HOST=sid3.comp.nus.edu.sg)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sid3.comp.nus.edu.sg)))');
        $sql="ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24:MI'";
        $stid=oci_parse($dbh, $sql);
        oci_execute($stid);
        date_default_timezone_set ('Singapore');

// username and password sent from form 
$myusername=$_POST['username']; 
$mypassword=$_POST['password']; 
$tbl_name = 'admin';


/*testing only
$sql="SELECT * FROM $tbl_name";


$stid=oci_parse($dbh,$sql);
oci_execute($stid,OCI_DEFAULT);

echo "start";
echo "<table>\n";
$ncols = oci_num_fields($stid);
echo "<tr>\n";
for ($i = 1; $i <= $ncols; ++$i) {
    $colname = oci_field_name($stid, $i);
    echo "  <th><b>".htmlentities($colname, ENT_QUOTES)."</b></th>\n";
}
echo "</tr>\n";

while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
     echo "<tr>\n";
     foreach ($row as $item) {
          echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES):" ")."</td>\n";
     }
    echo "</tr>\n";
}
echo "</table>\n";
echo "end";
// testing only
*/


$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";

$stid=oci_parse($dbh,$sql);
oci_execute($stid,OCI_DEFAULT);

$count=oci_fetch_all($stid, $result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
// Register $myusername, $mypassword and redirect to file "login_success.php"
header("location:admin.php");
}
else {
//echo "count: $count";
echo "username entered: $myusername<Br>";
//echo "password entered: $mypassword<Br>";
echo "Wrong Username or Password";
}
?>
