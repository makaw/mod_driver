<?php


/*
 * 
 *  Somado - mod_driver
 * 
 *  Klasa User - użytkownicy systemowi - kierowcy
 * 
 */


class User {
	

   	private $dbh;

	private $uid = 0;
	private $login = null;
	private $pass = null;
	
	private $firstname = null;
	private $surname = null;
	
	private $driver_role;
	
	private $authError;
	
	
   /*  konstruktor 
    *  inicjalizacja atrybutow, ewentualna autoryzacja lub wylogowanie
    *  parametr $input - tablica parametrow wejsciowych
    */	
	public function __construct($input=null) {
		
		
		if (!isset($_SESSION['u_login'])) $_SESSION['u_login'] = null;
		if (!isset($_SESSION['u_pass'])) $_SESSION['u_pass'] = null;
		
		require '../www/config.php';
		$this->driver_role = $DRIVER_ROLE_ID;
		
  		try {
		
		  $this->dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
		  $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  $this->dbh->query("SET NAMES 'utf8';");
	
		 } catch(PDOException $e) {
	
	  	   echo $e->getMessage();
	
		 }
		
		/* ustawienie referencji do zmiennych sesyjnych */
	    $this->login = &$_SESSION['u_login'];
	    $this->pass = &$_SESSION['u_pass'];
	    $this->authError = &$_SESSION['u_autherror'];
	    
		
		$this->auth($_REQUEST);
		
	}
	
	

	
	/* autoryzacja, jezeli sa przekazane odpowiednie parametry */
	private function auth($input) {
		
		
	   if (isset($input['u_login']) && isset($input['u_pass']) 
			&& !empty($input['u_login']) && !empty($input['u_pass'])) {
		   
		  $this->login = $input['u_login'];
		  $this->pass = $input['u_pass'];  
		   
	   }	
	   
	   /*  przekazane parametry, wiec sprawdzenie loginu i hasla */
	   if ((!empty($this->login) && !empty($this->pass))
	   		|| (isset($input['submit']) && !empty($input['submit']))) {
			
			$query = "SELECT id, firstname, surname FROM sys_users "
					."WHERE login=:login AND passwd=PASSWORD(:passwd) AND blocked<>'1' AND role=:role ;";
			
			try {
			  $stmt = $this->dbh->prepare($query);  
			  $stmt->bindParam("login", $this->login);
			  $stmt->bindParam("passwd", $this->pass);	
			  $stmt->bindParam("role", $this->driver_role);		  
			  $stmt->execute();
			  if ($stmt->rowCount()>0) {
			    $user = $stmt->fetchObject();			    
			    $this->firstname = $user->firstname;
			    $this->surname = $user->surname;
			    $this->uid = $user->id;
			  }
			 else throw new PDOException();
		    }
		    
		    
		    catch(PDOException $e) {
			  echo $e->getMessage();
			  $this->uid = 0;
			  $this->firstname = null;
			  $this->surname = null;
			  $this->authError = 1;
			}   
		    		 		
		}	   		
		
	}	
	
	
	public function isAuthError() {
	
		return $this->authError == 1;
	
	}
	
	
	public function unsetAuthError() {
	
		$this->authError = null;
	
	}
	
	
	/*  sprawdza czy zalogowany */
	public function isAuthorized() {
		
	  return ($this->uid>0);	
		
	}
	
	/*  pobranie id uzytkownika */
	public function getUID() {
		
	   return $this->uid;	
		
	}
	
	/* pobranie loginu uzytkownika */
	public function getLogin() {
		
       return $this->login;		
		
	}
	
	/* pobranie hasla */
	public function getPass() {
		
	   return $this->pass;	
		
	}
	
	
	/*  wylogowanie */
	public function logOut() {
		
	   $this->login = null;
	   $this->pass = null;
		
	}
	
	
	public function toString()  {
		
	   return $this->login . " (" . $this->surname . " " . substr($this->firstname, 0, 1). ".)";	
		
	}
	

}

