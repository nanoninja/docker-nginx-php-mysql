<?php
namespace Acme\Controllers;

use Acme\Interfaces\ControllerInterface;
use duncan3dc\Laravel\BladeInstance;
use Kunststube\CSRFP\SignatureGenerator;
use Acme\Http\Response;
use Acme\Http\Request;
use Acme\Http\Session;

/**
 * Class BaseController
 * @package Acme\Controllers
 */
class BaseController implements ControllerInterface {

    /**
     * @var BladeInstance
     */
    protected $blade;

    /**
     * @var SignatureGenerator
     */
    protected $signer;

    /**
     * @var Response
     */
    public $response;

    /**
     * @var Request
     */
    public $request;


    /**
     * @param string $type
     */
    public function __construct($type = "text/html")
    {
        $this->signer = new SignatureGenerator(getenv('CSRF_SECRET'));
        $this->blade = new BladeInstance(getenv('VIEWS_DIRECTORY'), getenv('CACHE_DIRECTORY'));
        $this->request = new Request($_REQUEST, $_GET, $_POST);
        $this->response = new Response($this->request);
        $this->session = new Session();
    }

}
