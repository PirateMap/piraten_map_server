<?php
class Data_User extends Data_Abstract
{
  private $id;

  private $username;

  private $password;

  private $email;

  private $hash;

  private $admin;

  public function __construct()
  {
  }

  public function getId()
  {
    return $this->id;
  }

  private function setId($id)
  {
    if ($this->id) {
      throw new Exception('Changing Id is not possible');
    }

    $this->id = (int) $id;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function setUsername($username)
  {
    $username = strtolower($username);
    $this->username = $username;
    $this->logModification('username');
    return $this;
  }

  private function getPassword()
  {
    return $this->password;
  }

  public function setPassword($password)
  {
    $this->password = crypt($password, $this->getHash());
    $this->logModification('password');
    return $this;
  }

  public function setRandomPassword() {
    $chars = "abcdefghijkmnopqrstuvwxyz-_~!+%&§@<()>?ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
    $len = rand(7, 10);
    $pass = ""; 

    while (strlen($pass) <= $len) {
        $num = rand(0, strlen($chars));
        $pass = $pass . substr($chars, $num, 1); 
    }
    $this->setPassword($pass);
    return $pass;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('This is not a valid email address.');
    }
    $this->email = $email;
    $this->logModification('email');
    return $this;
  }

  public function getHash()
  {
    if (!$this->hash) {
      $this->hash = md5(gmmktime() . $this->getUsername());
      $this->logModification('hash');
    }
    return $this->hash;
  }

  public function getAdmin() {
    return $this->admin;
  }

  public function setAdmin($admin) {
    if ($admin !== true && $admin !== false)
        throw new Exception('This is not a valid admin value');
    $this->admin = $admin;
    $this->logModification('admin');
    return $this;
  }


  public static function find($attribute, $value)
  {
    if (!array_key_exists($attribute, get_class_vars(__CLASS__))) {
      throw new Exception('Attribute does not exist');
    }

    return System::query('SELECT * FROM ' . System::getConfig('tbl_prefix') . 'users WHERE ' . $attribute . '=?', $value);
  }

  public static function login($username, $password)
  {
    if ($username == '' || $password == '') {
      throw new Exception('Wrong password');
    }
    $username = strtolower($username);
    $result = System::query('SELECT * FROM ' . System::getConfig('tbl_prefix') . 'users WHERE username=? AND password=ENCRYPT(?, hash)', array($username, $password));
    if ($result->rowCount() != 1) {
      return self::login_deprecated($username, $password);
    }
    $user = $result->fetchObject(__CLASS__);

    return $user;
  }

  private static function login_deprecated($username, $password)
  {
    $result = System::query('SELECT * FROM ' . System::getConfig('tbl_prefix') . 'users WHERE username=? AND password=MD5(?)', array($username, $password));
    if ($result->rowCount() != 1) {
      throw new Exception('Wrong password');
    }
    //Fix password
    $user = $result->fetchObject(__CLASS__);
    $user->setPassword($password);
    $user->save();

    return $user;
  }

  public function save()
  {
    if (!$this->validate()) {
      return false;
    }
    if ($this->getId()) {
      $setvars = array();
      $setvals = array();
      foreach ($this->getModifications() as $variable) {
        $setvars[] = $variable . '=?';
        $setvals[] = $this->$variable;
      }

      if (empty($setvars)) {
        return 0;
      }

      $setvals[] = $this->getId();
      System::query('UPDATE ' . System::getConfig('tbl_prefix') . 'users SET ' . implode(', ', $setvars) . ' WHERE id=?', $setvals);
    } else {}
        $this->setId(System::query('INSERT INTO ' . System::getConfig('tbl_prefix') . 'users (username, password, active, hash) VALUES (?, ?, ?, ?, ?)',
                               array($this->getUsername(), $this->getPassword(), $this->getActive(), $this->getHash())));
    }

    return $this;
  }

  public function validate()
  {
    if (!$this->getUsername()) {
      return false;
    }
    if (!$this->getPassword()) {
      return false;
    }
    if (!$this->getEmail()) {
      return false;
    }


    return true;
  }
}