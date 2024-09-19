<?php
class Session extends SessionHandler 
{ 

    protected $salt, $key, $name, $cookie, $ip;

    public function __construct($salt, $name = DEFAULT_SESSION_NAME, $cookie = []) {
        //$_SESSION       = [];
        
        $this->ip = (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))
          ? (string) $_SERVER['HTTP_CF_CONNECTING_IP']
          : ((!empty($_SERVER['REMOTE_ADDR'])) ? (string) $_SERVER['REMOTE_ADDR'] : '');

        
        $this->salt     = $salt;
        $this->name     = $name;
        $this->cookie   = $cookie;

        $this->cookie += [
            'lifetime' => 0,
            'path'     => ini_get('session.cookie_path'),
            'domain'   => ini_get('session.cookie_domain'),
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true
        ];

        $this->setup();
    }
    
    protected function _randomKey($length=32) {
      if(function_exists('openssl_random_pseudo_bytes')) {
        $rnd = openssl_random_pseudo_bytes($length, $strong);
        if ($strong === true) {
            return $rnd;
        }
      }
      return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
    }

    private function setup() {
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);

        session_name($this->name);

        session_set_cookie_params(
            $this->cookie['lifetime'],
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
    }

    public function start() {
        if (session_id() === '') {
            if (session_start()) {
                $this->key = session_id();

                //return mt_rand(0, 4) === 0 ? $this->refresh() : true; // 1/5
            }
        }

        return false;
    }

    public function forget($id = '') {
        if (session_id() === '') {
            return false;
        }

        $_SESSION = [];

        setcookie(
            $this->name,
            '',
            time() - COOKIE_TIME_OUT,
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
        
        return $this->destroy($id);
        
    }
    
    public function destroy($id) {
        /*$sess_file = SESSION_PATH.'/'.($this->key.$id);
	    @unlink($sess_file);*/
        
        return parent::destroy($id);
    }

    public function refresh() {
        return session_regenerate_id(true);
    }

    public function read($id) {
        if(!$this->key)
            $this->key = session_id();
        
        $id = ($this->key.$id);
        return (string)openssl_decrypt (parent::read($id) , "aes-256-ecb", $this->salt);
    }

    public function write($id, $data) {
        if(!$this->key)
            $this->key = session_id();
        
        $id = ($this->key.$id);
        return parent::write($id, openssl_encrypt($data, "aes-256-ecb", $this->salt));
    }

    public function isExpired($ttl = 30) {
        $last = isset($_SESSION['_last_activity'])
            ? $_SESSION['_last_activity']
            : false;

        if ($last !== false && time() - $last > $ttl * 60) {
            return true;
        }

        $_SESSION['_last_activity'] = time();

        return false;
    }

    public function isFingerprint() {
        $hash = md5(
            $_SERVER['HTTP_USER_AGENT'] .
            (ip2long($this->ip) & ip2long('255.255.0.0'))
        );

        if (isset($_SESSION['_fingerprint'])) {
            return $_SESSION['_fingerprint'] === $hash;
        }

        $_SESSION['_fingerprint'] = $hash;

        return true;
    }

    public function isValid($ttl = 30) {
        return ! $this->isExpired($ttl) && $this->isFingerprint();
    }

    public function get($name) {
        // prevent the session is started
        if (session_id() === '') {$this->start();}
        
        $parsed = explode('.', $name);

        $result = $_SESSION;

        while ($parsed) {
            $next = array_shift($parsed);

            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return null;
            }
        }

        return $result;
    }

    public function put($name, $value) {
        // prevent the session is started
        if (session_id() === '') {$this->start();}
        
        $parsed = explode('.', $name);

        $session =& $_SESSION;

        while (count($parsed) > 1) {
            $next = array_shift($parsed);

            if ( ! isset($session[$next]) || ! is_array($session[$next])) {
                $session[$next] = [];
            }

            $session =& $session[$next];
        }

        $session[array_shift($parsed)] = $value;
    }
}
?>