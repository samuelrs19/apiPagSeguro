<?php
require_once __DIR__ . "/../vendor/autoload.php";

class PagSeguro
{

    private $ambiente;
    private $email;
    private $token;
    private $urlBase;

    function __construct()
    {

        //$this->ambiente = 'production';
        $this->ambiente = 'sandbox';

        $this->email = 'samrs2012@gmail.com';
        $this->token = '44BFAE8AA8DC4766A2191E3102633B43';

        if ($this->ambiente == 'sandbox') {
            $this->urlBase = "https://ws.sandbox.pagseguro.uol.com.br/v2/";
        } else {
            $this->urlBase = "https://ws.pagseguro.uol.com.br/v2/";
        }

        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Configuration\Configure::setEnvironment($this->ambiente); //production or sandbox
        \PagSeguro\Configuration\Configure::setAccountCredentials(
            $this->email,
            $this->token
        );
        \PagSeguro\Configuration\Configure::setCharset('UTF-8');
    }

    public function get($value)
    {
        return $this->$value;
    }

    public function set($value)
    {
        $this->value = $value;
    }

    public function getSessionId()
    {

        $return['v'] = false;
        $return['msg'] = '';
        $return['sessao'] = '';

        try {

            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            $return['v'] = true;
            $return['msg'] = 'Sucesso';
            $return['sessao'] = $sessionCode->getResult();
        } catch (Exception $ex) {

            $return['v'] = false;

            $code = $ex->getCode();

            $return['msg'] = '';
            if ($code == 401) {
                $return['msg'] = 'Credências não autorizada no pagSeguro';
            } else {
                $return['msg'] = 'Houve algum erro no PagSeguro. Erro code - ' . $code;
            }
        }

        return $return;
    }

    public function criarPlano()
    {

        $plan = new \PagSeguro\Domains\Requests\DirectPreApproval\Plan();

        $plan->setRedirectURL('http://meusite.com');
        $plan->setReference('http://meusite.com');
        $plan->setPreApproval()->setName('Plano XXXX');
        $plan->setPreApproval()->setCharge('AUTO');
        $plan->setPreApproval()->setPeriod('MONTHLY');
        $plan->setPreApproval()->setAmountPerPayment('100.00');
        //$plan->setPreApproval()->setTrialPeriodDuration(28);
        $plan->setPreApproval()->setDetails('detalhes do plano');
        //$plan->setPreApproval()->setFinalDate('2018-09-03');
        $plan->setPreApproval()->setCancelURL("http://meusite.com");
        $plan->setReviewURL('http://meusite.com./review');
        //$plan->setMaxUses(100);
        $plan->setReceiver()->withParameters($this->email);

        try {

            $response = $plan->register(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            echo '<pre>';
            print_r($response);
            echo '</pre>';
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function cancelarPlano($code)
    {

        $status = new \PagSeguro\Domains\Requests\DirectPreApproval\Cancel();
        $status->setPreApprovalCode($code);

        try {

            $response = $status->register(
                new \PagSeguro\Domains\AccountCredentials($this->get('email'), $this->get('token')) // credencias do vendedor no pagseguro
            );

            echo '<pre>';
            print_r($response);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
