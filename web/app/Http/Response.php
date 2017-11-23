<?php
namespace Acme\Http;

use duncan3dc\Laravel\BladeInstance;
use Kunststube\CSRFP\SignatureGenerator;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Class Response
 * @package Acme\Http
 */
class Response {

    protected $data;
    protected $view;
    protected $with;
    protected $response_type;
    protected $response_code;
    protected $flash;
    protected $signer;
    protected $session;
    protected $with_input;
    protected $request;

    /**
     * Constructor
     */
    public function __construct(Request $request, SignatureGenerator $signer, BladeInstance $blade, Session $session)
    {
        $this->request = $request;
        $this->blade = $blade;
        $this->response_type = 'text/html';
        $this->signer = $signer;
        $this->with['signer'] = $this->signer;
        $this->session = new Session();
        $this->with_input = false;
    }


    /**
     * Render a page
     */
    public function render()
    {
        $this->with['_session'] = $this->session;
        $html = $this->blade->render($this->view, $this->with);
        $this->repopulateForm($html);
        $this->renderOutput($html);
    }


    /**
     * @return $this
     */
    public function toJson()
    {
        $this->response_type = 'application/json';

        return $this;
    }


    /**
     * @return $this
     */
    public function toXml()
    {
        $this->response_type = 'text/xml';

        return $this;
    }


    /**
     * @param $view
     * @return $this
     */
    public function withView($view)
    {
        $this->view = $view;

        return $this;
    }


    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function with($name, $value)
    {
        $this->with[$name] = $value;

        return $this;
    }


    /**
     * @param $code
     * @return $this
     */
    public function withResponseCode($code)
    {
        $this->response_code = $code;

        return $this;
    }


    /**
     * @param $message
     * @return $this
     */
    public function withError($message)
    {
        $this->session->put('_error', $message);

        return $this;
    }


    /**
     * @param $message
     * @return $this
     */
    public function withMessage($message)
    {
        $this->session->put('_message', $message);

        return $this;
    }


    /**
     * @param $target
     */
    public function redirectTo($target)
    {
        header("Location: " . $target);
    }


    /**
     *
     */
    public function withInput()
    {
        $this->with_input = true;
    }


    /**
     * @param $html
     * @return mixed
     */
    private function repopulateForm($html)
    {
        if ($this->with_input) {
            $keys = $this->request->getPost();
            $dom = HtmlDomParser::str_get_html($html);

            foreach ($keys as $name => $value) {
                $elements = $dom->find('#' . $name);
                foreach ($elements as $element) {
                    $tag = $element->tag;

                    switch ($tag) {
                        case ("input"):
                            if (isset($element->value))
                                $element->value = $value;
                            break;
                        case ("textarea"):
                            $element->innertext = $value;
                            break;
                        default:
                            // nothing
                    }
                }
            }
            $html = $dom->save();
        }

        return $html;
    }


    /**
     * @param $payload
     */
    private function renderOutput($payload)
    {
        if ($this->response_code != null) {
            switch ($this->response_code) {
                case (404):
                    header("HTTP/1.1 404 Not Found");
                    break;
                default:
                    // nothing
            }
        }

        header('Content-Type: ' . $this->response_type);
        echo $payload;

        if ($this->session->has('_message'))
            $this->session->forget('_message');

        if ($this->session->has('_error'))
            $this->session->forget('_error');
    }

}
