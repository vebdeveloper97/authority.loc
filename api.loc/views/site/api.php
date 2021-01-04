<a href="<?=\yii\helpers\Url::to(['site/save-db'])?>" class="btn btn-success btn-xs">Malumotlarni bazaga saqlash</a>
<br>
<?php
    /* @var $data */
    if($data['rates']){
        echo "<select class='from'><option value='null'>Tanlang...</option>";
        ?>
            <?php foreach ($data['rates'] as $key => $quote): ?>
                <option value="<?=$key?>"><?=$key?></option>
            <?php endforeach; ?>
        <?php
        echo "</select>";
        echo "<input type='text' id='currency_from'>";
        echo '<hr>';
        echo '<div>Exchange rate: <strong id="rate"></strong></div>';
        echo '<hr>';
        echo "<select class='to'><option value='null'>Tanlang...</option>";
        ?>
            <?php foreach($data['rates'] as $key => $quote): ?>
                <option value="<?=$key?>"><?=$key?></option>
            <?php endforeach; ?>
        <?php
        echo "</select>";
        echo "<input type='text' id='currency_to'>";
    }
    else{

    }
$fromCurrency = \yii\helpers\Url::to(['site/from-currency']);

$js =<<< JS

    let fromRate = 0;
    let toRate = 0;
    
    $('.from').change(function(){
        let fromVal = $(this).val();
        $.ajax({
            type: 'GET',
            data: {quotes: fromVal},
            url: "$fromCurrency",
            success: function (res){
                if(res.status){
                    for (const [key, value] of Object.entries(res.result.rates)) {
                        $('#rate').html(Math.round(value));
                        fromRate = Math.round(value);
                        console.log(fromRate);
                    }
                    // if($('#currency_from').val()){
                    //     let currencyFrom = Number($('#currency_from').val());
                    //     let sum = currencyFrom / rate;
                    //     $('#currency_to').val(sum);
                    // }
                }
                else{
                    
                }
            }
        });
    });    
    
    $('#currency_from').keyup(function (e){
       let currencyFrom = Number($(this).val());
       let cto = $('.to').val();
       console.log(cto);
       let sum = 0;
       if(rate != 0 && cto != 'null'){
           sum = currencyFrom * toRate;
           $('#currency_to').val(sum);
       }
       else{
           
       }       
    });
    
    $('.to').change(function (e){
           let fromVal = $(this).val();
        $.ajax({
            type: 'GET',
            data: {quotes: fromVal},
            url: "$fromCurrency",
            success: function (res){
                if(res.status){
                    for (const [key, value] of Object.entries(res.result.rates)) {
                        toRate = Math.round(value);
                        console.log(value);
                    }
                    // if($('#currency_from').val()){
                    //     let currencyFrom = Number($('#currency_from').val());
                    //     let sum = currencyFrom / rate;
                    //     $('#currency_to').val(sum);
                    // }
                }
                else{
                    
                }
            }
        });
       })
JS;

$this->registerJs($js);
