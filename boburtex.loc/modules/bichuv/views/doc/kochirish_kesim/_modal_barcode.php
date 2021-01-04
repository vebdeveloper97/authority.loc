<?php

use yii\bootstrap\Modal;
?>

<?php
Modal::begin([
    'size' => Modal::SIZE_DEFAULT,
    'id' => 'modal-barcode'
]);
?>
<div class="modal-header" style="display:none"></div>
    <div class="modal-body">
        <table class="table responstable table-bordered table-striped table-condensed table-hover" id="modal-table">
            <thead>
            <tr>
                <th>№</th>
                <th><?=Yii::t('app','Detail Card Number')?></th>
                <th><?=Yii::t('app','Bichuv Detail Type ID')?></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php
Modal::end();
?>
<?php $this->registerCss("
#modal-table tr{cursor:pointer} 
#modal-table tr td{padding:7px 10px!important; font-size: 18px}
#modal-table tr:hover{background-color: #00C0EF;color:white}
.responstable {
	 margin: 10px 0;
	 width: 100%;
	 overflow: hidden;
	 background: #fff;
	 color: #024457;
	 border-radius: 5px;
	 border: 1px solid #167f92;
}
 .responstable tr {
	 border: 1px solid #d9e4e6;
}
 .responstable tr:nth-child(odd) {
	 background-color: #eaf3f3;
}
 .responstable th {
	 display: none;
	 border: 1px solid #fff;
	 background-color: #167f92;
	 color: #fff;
}

 .responstable th:first-child {
	 display: table-cell;
	 text-align: center;
}
 .responstable th:nth-child(2) {
	 display: table-cell;
}
 .responstable th:nth-child(2) span {
	 display: none;
}
 .responstable th:nth-child(2):after {
	 content: attr(data-th);
}
 @media (min-width: 480px) {
	 .responstable th:nth-child(2) span {
		 display: block;
	}
	 .responstable th:nth-child(2):after {
		 display: none;
	}
}
 .responstable td {
	 display: block;
	 word-wrap: break-word;
	
}
 .responstable td:first-child {
	 display: table-cell;
	 text-align: center;
	 border-right: 1px solid #d9e4e6;
}
 @media (min-width: 480px) {
	 .responstable td {
		 border: 1px solid #d9e4e6;
	}
}
 .responstable th, .responstable td {
	 text-align: left;
	 margin: 5px 10px;
}
 @media (min-width: 480px) {
	 .responstable th{
		 display: table-cell;
		 padding: 10px;
	}
	.responstable td {
	    display: table-cell;
		 padding: 5px;
	}
}

")?>
