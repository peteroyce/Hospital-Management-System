<?php
include('include/config.php');
if(!empty($_POST["specilizationid"]))
{
 $stmt = $con->prepare("SELECT doctorName, id FROM doctors WHERE specilization = ?");
 $stmt->bind_param("s", $_POST['specilizationid']);
 $stmt->execute();
 $result = $stmt->get_result();
?>
 <option selected="selected">Select Doctor </option>
 <?php
 while($row = $result->fetch_assoc())
 	{?>
  <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['doctorName']); ?></option>
  <?php
}
 $stmt->close();
}


if(!empty($_POST["doctor"]))
{
 $stmt = $con->prepare("SELECT docFees FROM doctors WHERE id = ?");
 $stmt->bind_param("s", $_POST['doctor']);
 $stmt->execute();
 $result = $stmt->get_result();
 while($row = $result->fetch_assoc())
 	{?>
 <option value="<?php echo htmlentities($row['docFees']); ?>"><?php echo htmlentities($row['docFees']); ?></option>
  <?php
}
 $stmt->close();
}

?>

