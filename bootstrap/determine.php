<?php
/**
 * Determine Platform CLI bootstrap
 *
 * Forced into the global namespace
 *
 * @var string $__custIdToUse If value is set, it will be used as the CUST_ID. The default is to use "product"
 * @var string $__coreToUse   If value is set, it will be used as the BPACK_CORE. The default is to use "trunk"
 */

//******************************************************************************
//* Declarations
//******************************************************************************

/**
 * @var string The default customer ID if none specified
 */
const DTRM_DEFAULT_CUST_ID = 'product';
/**
 * @var string The name of the database for the default customer
 */
const DTRM_DEFAULT_DB_NAME = 'PRODUCT';
/**
 * @var string The name of framework-core directory to use
 */
const DTRM_DEFAULT_CORE = 'trunk';

/**
 * @var string          $ROOT
 * @var array           $appconf
 * @var string          $AROOT
 * @var string          $APATH
 * @var string          $APPLI
 * @var string          $CPATH
 * @var string          $BPATH
 * @var ApcCache|XCache $LocalCache
 * @var array           $StrMsg
 * @var array           $_SERVER
 * @var string          $__basePath The discovered platform root path
 */
global $ROOT, $MainDB, $bdb, $AUTHFIELD, $appconf, $AROOT, $APATH, $APPLI,
       $CPATH, $BPATH, $LocalCache, $LPATH, $MPATH, $NROOT, $PROOT, $SPATH,
       $StrMsg, $__coreToUse, $__custIdToUse, $Module, $UserAccess;

//  Make sure we have a $_SERVER and $__custIdToUse variable
!isset($_SERVER) and $_SERVER = [];
!isset($__custIdToUse) and $__custIdToUse = DTRM_DEFAULT_CUST_ID;
!isset($__coreToUse) and $__coreToUse = DTRM_DEFAULT_CORE;

//******************************************************************************
//* Logic
//******************************************************************************

if (false === ($__basePath = __findPlatformRoot(__DIR__))) {
    __abort(500, 'Internal Server Error', '<h2>Internal Server Error</h2><p>Cannot find the application root directory.</p>');
}

$__basePath .= DIRECTORY_SEPARATOR;

//  Setup the $_SERVER variable for CLI
__setServerVars($__basePath, $__custIdToUse, $__coreToUse);

//  Base points to root of tree
$ROOT = rtrim(realpath($__basePath . DIRECTORY_SEPARATOR . $__coreToUse . '/html/'), ' ' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

/** @noinspection PhpIncludeInspection */
include_once $_SERVER['BPACK_CONFIG'] . 'config.inc';

if (empty($appconf)) {
    __abort(404, 'Configuration file not found.', 'Application Error');
}

if (!empty($APPLI) && (empty($AROOT) || empty($APATH))) {
    if (empty($AROOT)) {
        $AROOT = $APPLI . '/';
    }

    if (empty($APATH)) {
        $APATH = $ROOT . $AROOT;
    }
}

//  B-pack path
$BPATH = $ROOT . 'bpsrc/';
//  B-pack common path
$CPATH = $ROOT . 'common/';

/** Chargement du fichier de modelisation de l'application (constantes) */
/** @noinspection PhpIncludeInspection */
include_once $CPATH . 'include/version.inc';

// If we trace execution times, initiate it and set the proper flag
$trace_page = 0;

if (!empty($appconf['PgTraceMin']) || !empty($appconf['Debug'])) {
    /** @noinspection PhpIncludeInspection */
    if (include_once($BPATH . '/include/trace.inc')) {
        LogPageExe(1);
        $trace_page = 1;
    }
}

//  Kajigger the root for these includes
!isset($LPATH) and $LPATH = $ROOT . '../lib/';

/** @noinspection PhpIncludeInspection */
require_once $BPATH . '/error.inc';
/** @noinspection PhpIncludeInspection */
require_once $BPATH . '/db/data.inc';
/** @noinspection PhpIncludeInspection */
require_once $BPATH . '/include/libfunc.inc';
/** @noinspection PhpIncludeInspection */
require_once $CPATH . '/include/setup.inc';

//  Get the session Id passed from Flash. To secure acces, we use token with period validity
$__token = BPRequest('stoken', 'char', false, false, '_POST');

if (!empty($__token)) {
    $__params = unserialize(bkey_decrypt($__token));

    if (!empty($__params[session_name()]) && !empty($__params['validity']) && $__params['validity'] > (time())) {
        session_id($__params[session_name()]);
    }
}

//  profil pour les droits d'acces
if (empty($AUTHFIELD)) {
    $AUTHFIELD = strtoupper('P' . $APPLI);
}

/** Chargement de la librairie de fonctions specifiques aux applications */
/** @noinspection PhpIncludeInspection */
include_once $APATH . '/include/appli.inc';

if (!defined('HTTP_LOGIN')) {
    defined('SSL_PROTECTED') or define('SSL_PROTECTED', 0);
    $__protocol = SSL_PROTECTED ? 'https://' : 'http://';

    define('HTTP_LOGIN', $__protocol);
    define('HTTP_PROTECTED', $__protocol);
    define('HTTP_EXCEL', $__protocol);
    define('HTTP_NOTPROTECTED', SSL_PROTECTED ? (SSL_PROTECTED & 0x02) ? 'https://' : 'http://' : 'http://');

    unset($__protocol);
}

//  Ensure to run the site in HTTPS
if (!checkHttps()) {
    header('Location: ' . HTTP_NOTPROTECTED . $appconf['hostname'] . $_SERVER['REQUEST_URI']);
    die;
}

$PROOT = HTTP_PROTECTED . $appconf['rooturl'] . 'protected/';
$NROOT = HTTP_PROTECTED . $appconf['rooturl'];

/****************************************************/
/* Definition des chemins par defaut si non definis */
/****************************************************/

// Chemin des librairies par defaut
!isset($LPATH) and $LPATH = $ROOT . '../lib/';

// Chemin des modules si non defini dans le config.inc
!isset($MPATH) and $MPATH = $ROOT . 'modules/';

// Upload file root (to match URL access root from the server disk)
!isset($appconf['root_upload']) and $appconf['root_upload'] = $appconf['rootpath'];

// Secure Key default location, relative to the directory for the "files"
!isset($appconf['key_path']) and $appconf['key_path'] = 'keys/';

//  Set default Charset encoding
empty($appconf['charset']) and $appconf['charset'] = 'utf-8';

ini_set('default_charset', $appconf['charset']);
mb_internal_encoding($appconf['charset']);

$UserAccess = '';

if (!isset($MM_authorizedUsers)) {
    $MM_authorizedUsers = 'use';
}

/** @noinspection PhpIncludeInspection */
include_once($GLOBALS['BPATH'] . 'core/container.class.php');

//  Initialize the execution container (we need the two syntaxes because code uses both) and start session
$container = $Container = new container();
$Container->ReadSession($MM_authorizedUsers);

//  Look for unique user's connexion
$UserDeconnected = 0;

//  À usage des tests unitaires.
//  @TODO faille de sécurité
if (!empty($_POST['BATCH_USERID']) && !empty($_POST['BATCH_PW']) && ($appconf['batch_passwd'] == $_POST['BATCH_PW'])) {
    $MM_BenchUserID = $_POST['BATCH_USERID'];
}

/**
 * If we have in the session the application and if this application is different
 * than the one from the config This can be set for example on the supplier login
 * to the supplier portal
 */
if (!empty($_SESSION['MM_Appli'])) {
    if ($APPLI != $_SESSION['MM_Appli']) {
        $APPLI = $_SESSION['MM_Appli'];
        // Chemin de l'API par defaut
        $AROOT = $APPLI . '/';
        $APATH = $ROOT . $AROOT;
    }
}
// Check cache revision
if ($LocalCache && !empty($_SESSION['rev'])) {
    $rev = $LocalCache->get('_currev_');

    if (!$rev) {
        $LocalCache->add('_currev_', $_SESSION['rev']);
    } elseif ($rev != $_SESSION['rev']) {
        $LocalCache->flush(1);
        $LocalCache->add('_currev_', $_SESSION['rev']);
    }
}

if ($LocalCache && !empty($_SESSION['CacheId'])) {
    // Localcache must be differentiated in this case
    $LocalCache->AddIdentifier($_SESSION['CacheId']);
}

if (!empty($_SESSION['MM_AuthField'])) {
    // profil pour les droits d'acces
    $AUTHFIELD = $_SESSION['MM_AuthField'];
}

/** @noinspection PhpIncludeInspection */
require_once $ROOT . 'include/userfunc.inc';

//  Grant access for command-line utility
$MM_grantAccess = ('cli' === php_sapi_name()) ? 1 : 0;

// Cas de la gestion du login par un script de benchmark (éviterle passage par l'url ou post)
if (!empty($MM_BenchUserID) && !isset($_REQUEST['MM_BenchUserID'])) {
    // Save session to restore it if needed
    $SavedSession = $_SESSION;

    // Lecture de la position dans la base
    $MM_USERID = $MM_BenchUserID;
    $Container->SetSession($MM_USERID);
    $MM_grantAccess = $Container->GrantAccess;
} else {
    // If session has been opened
    if (!empty($_SESSION['MM_Userlogin'])) {
        // On recupere les habilitation. La virgule est pour avoir un strpos positif.
        $UserAccess = ',' . $_SESSION['MM_UserAuthorization'];

        // Verification de l'acces en fonction des droits demandes
        $MM_grantAccess = ('' == $MM_authorizedUsers) ? 1 : CheckRights($MM_authorizedUsers);
    }
}

//  Disable reading from cache when in Debug mode
if ($LocalCache && !empty($_SESSION['Debug'])) {
    $LocalCache->read = 0;
}

//  Set Environment global variables
SetConfig();

//  Librairie specifique a l'application
/** @noinspection PhpIncludeInspection */
include_once $APATH . 'include/data.inc';

//  No Cache in dev mode
if (!empty($_SESSION['nocacheMode'])) {
    ob_start();

    try {
        $now = new DateTime; // now

        // Deactivation of cache is availlable only for 30 min
        if ($_SESSION['disableNoCacheModeAt'] - $now->format("YmdHi") > 0) {
            // Deactivate all cache features
            $GLOBALS['LocalCache'] = $GLOBALS['MemCache'] = false;

            if (extension_loaded('Zend OPcache')) {
                ini_set('opcache.enable', 0);
            }
        } else {
            $_SESSION['nocacheMode'] = 0;
            unset($_SESSION['disableNoCacheModeAt']);

            // clear the cache before deactivate noCache mode
            if (extension_loaded('Zend OPcache')) {
                opcache_reset();
            }

            if ($GLOBALS['LocalCache']) {
                $GLOBALS['LocalCache']->flush();
            }

            if (!empty($GLOBALS['MemCache'])) {
                $GLOBALS['MemCache']->flush(0);
            }

            $lock = LockManager($GLOBALS['container']);
            $lock->Clean();

            $GLOBALS['Container']->db->CacheFlush();
        }
    } catch (Exception $e) {
    }

    ob_end_clean();
}

// Load data main structure, including main language declaration
LoadDbStruct();

/*************************/
/* Traitement du Timeout */
/*************************/

if (!$MM_grantAccess) {
    $urlparams = array();

    $MM_referrer = $_SERVER['PHP_SELF'];
    $addpage = false;

    if (mb_strlen($_SERVER['QUERY_STRING']) > 0) {
        $MM_referrer .= '?' . $_SERVER['QUERY_STRING'];
        $addpage = true;
    }

    /**
     * Grant or deny access to this page verifying timeout duration
     */
    if (isset($_REQUEST['MM_authFailedURL'])) {
        $MM_authFailedURL = $_REQUEST['MM_authFailedURL'];
    } elseif (!empty($_SERVER['HTTP_REFERER']) &&
        !empty($appconf['loginpages']) &&
        in_array(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), $appconf['loginpages'])
    ) {
        $refererInfo = parse_url($_SERVER['HTTP_REFERER']);
        $MM_authFailedURL = $_SERVER['HTTP_REFERER'];

        if (false !== strpos($MM_authFailedURL, '?')) {
            $MM_authFailedURL = substr($MM_authFailedURL, 0, strpos($MM_authFailedURL, '?'));
        }

        $queryArray = array();
        if (!empty($refererInfo['query'])) {
            // convert the query parameters to an array
            parse_str($refererInfo['query'], $queryArray);
        }
        unset($queryArray['forcemodal'], $queryArray['ErrorCode']);

        if (isset($_REQUEST['page'])) {
            $queryArray['page'] = $_REQUEST['page'];
        } elseif ($addpage) {
            $queryArray['page'] = $MM_referrer;
        }

        if (!empty($queryArray)) {
            $MM_authFailedURL .= '?' . http_build_query($queryArray);
        }
    } else {
        if (!empty($appconf['loginpage'])) {
            $MM_authFailedURL = $appconf['loginpage'];
        } else {
            $MM_authFailedURL = SSL_PROTECTED ? HTTP_LOGIN . $appconf['rooturl'] : $ROOT;

            // Chemin de la page de login (peut etre personnalisee)
            $lpage = (!empty($appconf['login']) ? $appconf['login'] : 'protected/login.php');
            $MM_authFailedURL .= $lpage;

            if (isset($_REQUEST['page'])) {
                $MM_authFailedURL .= '?page=' . urlencode($_REQUEST['page']);
            } elseif (!strpos($_SERVER['REQUEST_URI'], 'auto_unlog.php')) {
                $MM_authFailedURL .= '?page=' . urlencode($_SERVER['REQUEST_URI']);
            }

            $urlparams['MM_Appli'] = $APPLI;
        }
    }

    $err = empty($MM_authorizedUsers) ? '' : $StrMsg['AUTH_PAGE'];

    if ((!empty($MM_authorizedUsers)) && (!empty($UserAccess))) {
        sendlog('b-pack Software Credential Error (Access Denied) (' .
            $_SERVER['REMOTE_ADDR'] .
            ") on $MM_referrer (user=$UserAccess, required=$MM_authorizedUsers)",
            3,
            'SECURITY');
    } else {
        sendlog('b-pack Software deconnection (' . $_SERVER['REMOTE_ADDR'] . ") on $MM_referrer (user=$UserAccess, required=$MM_authorizedUsers)",
            1,
            'SECURITY');
        if (!empty($GLOBALS['objResponse'])) {
            sendlog('b-pack Software deconnection objResponse:', 1, 'SECURITY');
            sendlog($GLOBALS['objResponse'], 1, 'SECURITY');
        }
        sendlog('b-pack Software deconnection BackTrace:', 1, 'SECURITY');
        sendlog(returnBackTrace(), 1, 'SECURITY');
    }
    Unlog_user(false, 1);
    header('HTTP/1.0 401 Not Authorized');
    Redirection($MM_authFailedURL, 1, TOP_FRAME/*,$urlparams*/);
    exit(0);
}

$lasttime = time();
$_SESSION['lasttime'] = $lasttime;

// Par defaut l'utilisateur n'est ni proprietaire ni manager
$owner = $manager = $DocAccess = $MM_Excel = false;

// Update user's credential information
$Container->SetAccess();

$Display = $Return = null;

// Mise a jour des variables d'acces aux pages
if (!empty($_REQUEST['Display'])) {
    $Display = $_REQUEST['Display'];
}

if (!empty($_REQUEST['Return'])) {
    $Return = $_REQUEST['Return'];
}

/**
 * Format d'affichage des donnees
 * 0x01 = html
 * 0x02 = csv
 * 0x04 = Excel
 * 0x08 = pdf
 * 0x100 = Interaction html
 * 0x1000 = Format de donnees specifique
 */
if (!empty($_REQUEST['OutFmt'])) {
    $OutFmt = $_REQUEST['OutFmt'];

    if ($OutFmt & 0x04) {
        $MM_Excel = 'biff';
    }

    if ($OutFmt & 0x0E) {
        $MM_ReportPage = 2;
    }
} else {
    $OutFmt = 0x101;

    /** Pour garder la compatibilite ascendante */
    if (!empty($_REQUEST['MM_Excel'])) {
        $MM_Excel = $_REQUEST['MM_Excel'];
        if ($MM_Excel == 'biff') {
            $OutFmt = 4;
        }
    }
}

if (empty($AppConfig['DateFmt'])) {
    $AppConfig['DateFmt'] = 'd-m-Y';
}

/** Format d'affichage des dates */
defined('SETUP_DATE_FORMAT') or define('SETUP_DATE_FORMAT', empty($AppConfig['DateFmt']) ? 'd-m-Y' : $AppConfig['DateFmt']);

// be sure to secure start of year to avoid bad data crunching
if (empty($AppConfig['YearStart']) || $AppConfig['YearStart'] < 2000) {
    $AppConfig['YearStart'] = date('Y');
}

//  Gestion des themes de l'application
$GLOBALS['THEME_STANDARD_PATH'] = $ROOT . 'assets/css/';
$GLOBALS['THEME_PATH'] = GetThemeLink();
$GLOBALS['THEME_ROOTPATH'] = GetThemeLink(false);
$GLOBALS['ICON_PATH'] = GetIconLink();

//  Definition des parametres standards liés au thème
$appconf['theme_config'] = array();

Layout::initThemeSetup();

//  Manage data list autoload's functionality per data type
if (!isset($appconf['autoLoadData'])) {
    if (defined('SETUP_PERFORMANCE') && SETUP_PERFORMANCE & 0x10000) {
        // only the 'ref' data type are automatically loaded with this modeling
        $appconf['autoLoadData'] = array('ref');
    } else {
        $appconf['autoLoadData'] = array('ref', 'sys', 'trans', 'temp', 'log', 'folder');
    }
}

if (empty($appconf['pageSizeEnable'])) {
    $appconf['pageSizeEnable'] = array('20' => '20', '30' => '30', '50' => '50', '100' => '100');
}

$LinesPerPage = !empty($AppConfig['NbLines']) ? $AppConfig['NbLines'] : 20;

if (defined('SETUP_CART') && SETUP_CART & 0x80000 && empty($AppConfig['CompareLimit'])) {
    $AppConfig['CompareLimit'] = 5;
}

if (!isset($appconf['MIME_TYPES_NOTALLOWED'])) {
    $appconf['MIME_TYPES_NOTALLOWED'] = array(
        'text/php',
        'text/x-php',
        'application/php',
        'application/x-php',
        'application/x-httpd-php',
        'application/x-httpd-php-source',
    );
}

if (!isset($appconf['HTML_TAGS_ALLOWED'])) {
    //  List of allowed HTML (HTML4 and HTML5) tag
    $appconf['HTML_TAGS_ALLOWED'] =
        '<abbr><b><u><i><blockquote><cite><q><sup><sub><strong><em><h1><h2><h3><h4><h5><h6><br><p><hr><pre><ul><li><ol><dl><dd><dt><table><tfoot><tbody><thead><th><td><tr><caption><fieldset><span><div><a>';
}

//  Used to increase script execution time
defined('MAX_EXECUTION_SCRIPT') or define('MAX_EXECUTION_SCRIPT', 600);

//  Cleanup
unset($_configPath, $__basePath, $__params, $__token);

/**
 * Sets the customer id for the request and initializes the $_SERVER global if running in CLI mode.
 *
 * @param string      $basePath
 * @param string|null $custId
 * @param string|null $core The core to use. Defaults to "trunk"
 */
function __setServerVars($basePath, $custId = null, $core = null)
{
    //  Make sure these guys exist
    static $_vars = ['BPACK_CONFIG', 'BPACK_APPLI', 'BPACK_CORE', 'BPACK_CUST', 'DOCUMENT_ROOT', 'HTTP_HOST'];

    foreach ($_vars as $_var) {
        if (!isset($_SERVER[$_var])) {
            $_SERVER[$_var] = null;
        }
    }

    //  Try and find one if empty...
    if (!empty($custId)) {
        $_SERVER['BPACK_CUST'] = $custId;
    }

    if (!empty($core)) {
        $_SERVER['BPACK_APPLI'] = $core;
        $_SERVER['BPACK_CORE'] = $basePath . $core . DIRECTORY_SEPARATOR;
    }

    if ($basePath != $_SERVER['DOCUMENT_ROOT']) {
        $_SERVER['DOCUMENT_ROOT'] = $basePath;
    }

    if (($basePath . '/appli') != $_SERVER['BPACK_CONFIG']) {
        $_SERVER['BPACK_CONFIG'] = $basePath . '/appli/';
    }

    $_SERVER['HTTP_HOST'] = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
}

/**
 * @param string|null $start The directory to start the walk
 *
 * @return bool
 */
function __findPlatformRoot($start = null)
{
    $_path = realpath($start ?: './');

    while (true) {
        if (empty($_path) || DIRECTORY_SEPARATOR === $_path) {
            break;
        }

        if (is_dir($_path . '/apps') && is_dir($_path . '/appli')) {
            return realpath($_path);
        }

        $_path = dirname($_path);
    }

    return false;
}

/**
 * @param int    $code    The HTTP response code
 * @param string $message The HTTP response header message
 * @param string $html    HTML to render to the screen
 */
function __abort($code, $message, $html = null)
{
    header('HTTP/1.0 ' . $code . ' ' . $message);

    if (!empty($html)) {
        echo $html;
    }

    die;
}
