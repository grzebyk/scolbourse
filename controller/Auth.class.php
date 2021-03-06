<?php

  /**
   * File : BourseAuth.class.php
   *
   * @author RIVAS Ronel Ronel
   *
   *
   * @package controller
   */


/**
 *  La classe BourseAuth
 *
 *  La Classe BourseAuth realise l'authentification et le contr&ocirc;le d'acc&egrave;s pour l'application Biblioth&egrave;que.
 *  Elle utilise la table des adh&eacute;rents: chaque adh&eacute;rent poss&egrave;de un login, un password et un type qui correspond � son
 *  niveau de droits.
 *  
 */
 
/** 
 *  @package controller
 */
class Auth {

	public static $SESSION_VAR_NAME = "my_auth_sess_var";
	
	public static $ADMIN_LEVEL = 101;
	
	public static $USER_LEVEL = 1;

	/**
	* @access private
	* @var Utilisateur 
	* Reference interne vers la variable de session
	*/
	private $_intern_ref;
	
	public function __construct(){
		if( isset($_SESSION[self::$SESSION_VAR_NAME]) )
			$this->_intern_ref = $_SESSION[self::$SESSION_VAR_NAME];
		else
			$this->_intern_ref = null;
	}
	
	public function checkUserIdentity($login, $pass){
		$utilisateur = Utilisateur::findBylogin($login);
		$pass = md5($pass);
		//if(($utilisateur!=null)&&($utilisateur->checkPassword($pass))) {
        if (!is_null($utilisateur)&&($utilisateur->checkPassword($pass))) {
			// OK
			$this->_intern_ref = $utilisateur;
			$_SESSION[self::$SESSION_VAR_NAME] = $utilisateur;
                } else {
			// L'utilisateur n'existe pas
			unset($this->_intern_ref);
			unset($_SESSION[self::$SESSION_VAR_NAME]);
			throw new AuthException("Utilisateur ou mot de passe <strong>INCORRECTS</strong>...",0);
                }
		
	
	}
	
	public function checkAuthLevel($level){
		if(($this->_intern_ref==null)||($this->_intern_ref->getAttr("typeUtilisateur") < $level))
			throw new AuthException("Acces non valide",1);
	}
	
	public function getUserProfile() {
		return $this->_intern_ref;
	}
	
	public function getAttr($name) {
		return ($this->_intern_ref->getAttr($name));
	}
}
?>