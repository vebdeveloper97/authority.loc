<div>
    <button class="btn" onclick="printdiv('toPrint')">Print</button>
</div>

<?php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($model->barcode, $generator::TYPE_CODE_128));
    ?>
<div id="toPrint" style="margin-left: 5%">


    <?php for ($i = 0; $i < $quantity; $i++): ?>


        <p class="inline"><span><b>Item: <?= $model->barcode; ?></b></span>
            <?= '<img src="data:image/png;base64,' . $barcode . '">'?>
            <span><b>Price: ".$rate." </b><span></p>&nbsp&nbsp&nbsp&nbsp


    <?php endfor; ?>

</div>


<script>
    function printdiv(printdivname) {
        var oldstr = document.body.innerHTML;
        document.body.innerHTML = document.getElementById(printdivname).innerHTML;
        window.print();
        document.body.innerHTML = oldstr;
        return false;
    }
</script>