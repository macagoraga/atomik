<?php
/**
 * Atomik Framework
 * Copyright (c) 2008 Maxime Bouroumeau-Fuseau
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package Atomik
 * @subpackage Plugins
 * @author Maxime Bouroumeau-Fuseau
 * @copyright 2008 (c) Maxime Bouroumeau-Fuseau
 * @license http://www.opensource.org/licenses/mit-license.php
 * @link http://www.atomikframework.com
 */

/**
 * Helpers function for handling databases
 *
 * @package Atomik
 * @subpackage Plugins
 */
class DbPlugin
{
	/**
	 * Default configuration
	 * 
	 * @var array 
	 */
    public static $config = array (
    	
    	/* connection string (see PDO) */
    	'dsn' 			=> 'mysql:host=localhost;dbname=atomik',
    	
    	/* username */
    	'username'		=> 'root',
    	
    	/* password */
    	'password'		=> '',
    
        /* whether to auto connect */
        'autoconnect'	=> false
    	
    );
    
    /**
     * Plugin starts
     *
     * @param array $config
     */
    public static function start($config)
    {
    	self::$config = array_merge(self::$config, $config);
    	
    	/** Atomik_Db */
    	require_once 'Atomik/Db.php';

		/* automatic connection */
		if (isset(self::$config['autoconnect']) && self::$config['autoconnect'] == true) {
			$dsn = self::$config['dsn'];
			$username = self::$config['username'];
			$password = self::$config['password'];
			Atomik_Db::connect($dsn, $username, $password);
		}
		
		/* registers the db selector namespace */
		Atomik::registerSelector('db', array('DbPlugin', 'selector'));
    }
	
	/**
	 * Atomik selector
	 *
	 * @param string $selector
	 * @param array $params
	 */
	public static function selector($selector, $params = array())
	{
	    /* checks if only a table name is used */
	    if (preg_match('/^[a-z_\-]+$/', $selector)) {
	        return Atomik_Db::findAll($selector, $params);
	    }
	    
	    return Atomik_Db::query($selector, $params);
	}
}
