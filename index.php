<?php
require_once  "controle/PagSeguro.php";
$objPagSeguro = new PagSeguro();

$objPagSeguro->cancelarPlano('71B5E7084646508EE4198F868DA2CF97');
die;

$sessionId = $objPagSeguro->getSessionId()['sessao'];


echo "Pagamento recorrente";
?>
<html>

<head>
    <title>Pagamento recorrente!</title>
</head>

<body>
    <div class="container">
        <div id="returnPagamentoRecorrente"></div>
    </div>
</body>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#returnPagamentoRecorrente').html('<img id="gif" src="img/load.gif" />');

        PagSeguroDirectPayment.setSessionId('<?= $sessionId ?>');

        cardNumber = '4111111111111111';
        cardBrand = '';

        PagSeguroDirectPayment.getBrand({
            cardBin: cardNumber,
            success: function(response) {
                console.log(response);
                cardBrand = response.brand.name;
            },
            error: function(response) {
                console.log(response);
            }
        });

        PagSeguroDirectPayment.createCardToken({
            cardNumber: '4111111111111111',
            brand: '',
            cvv: '123',
            expirationMonth: '12',
            expirationYear: '2030',
            success: function(response) {
                console.log('sucesso: ', response);
                //criarPlano(response.card.token)
            },
            error: function(response) {
                console.log('error: ', response);
            }
        });
    });

    function criarPlano(token) {

        $.post("ajaxs/ajaxCriarPlano.php", {
            token: token
        }, function(response) {
            $('#returnPagamentoRecorrente').html(response);
        });
    }
</script>