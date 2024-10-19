<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin' && isset($_POST['due_date'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$title = validate_input($_POST['title']);
	$description = validate_input($_POST['description']);
	$assigned_to = validate_input($_POST['assigned_to']);
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Ajouter le titre";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if (empty($description)) {
		$em = "Ajouter la description";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if ($assigned_to == 0) {
		$em = "Selectonner Dev";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else {
    
       include "Model/Task.php";
       include "Model/Notification.php";

       $data = array($title, $description, $assigned_to, $due_date);
       insert_task($conn, $data);

       $notif_data = array("'$title' vous a ete attribué. Commencé à travailler", $assigned_to, 'Nouvelle tache attribuée');
       insert_notification($conn, $notif_data);


       $em = "Tache créé avec succès";
	    header("Location: ../create_task.php?success=$em");
	    exit();

    
	}
}else {
   $em = "Erreur";
   header("Location: ../create_task.php?error=$em");
   exit();
}

}else{ 
   $em = "Se connecter d'abord";
   header("Location: ../create_task.php?error=$em");
   exit();
}