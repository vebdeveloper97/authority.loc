<?php
$js = <<< JS
    
    '    <div class="col-md-1 col-w-12 aksessuar baski '+baski_class+'" data-hidden="'+baski_class+'" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_baski_id">' +
    '            <label>{$baski_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control baski_count input_count" id="baski_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+baski_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#baski-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="baski-modal_'+num+'" class="fade modal baski_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$baskilar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    '                                    {$height} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__baski_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_baski btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
                                    baski_tbody +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
?>
<script>
    let a = '<div id="toTextValue" class="hidden" style="position: absolute;top: 50px;left: -200px;background: orange;height:auto;overflow-y: scroll;z-index: 9999999999999999">' +
        '    <button type="button" class="pull-right close">' +
        '        <i class="fa fa-close"></i>' +
        '    </button>' +
        '    <table class="table" id="table_user" align="center">' +
        '        <tbody>' +
        '        <?php for ($i = 0;$i == 10;$i++){?>' +
        '            <tr>' +
        '                <td>' +
        '                    <button class="btn btn-default user_button" type="button" id="user_<?=$i?>">' +
        '                        hgrdhrhedrh' +
        '                    </button>' +
        '                </td>' +
        '            </tr>' +
        '        <?php }?>' +
        '        </tbody>' +
        '    </table>' +
        '</div>';
let b = '<div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">' +
    '      <div class="form-group field-modelordersitems-model_toquv_acs_id">' +
    '           <label>{$toquv_aksessuar}</label>' +
    '           <div class="input-group">' +
    '               <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_'+indeks+'" aria-describedby="basic-addon_'+indeks+'" value="0">' +
    '               <span class="input-group-addon btn btn-success" id="basic-addon_'+indeks+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_'+indeks+'"><i class="fa fa-plus"></i></span>' +
    '           </div>' +
    '       </div>' +
    '       <div id="toquv_acs-modal_'+indeks+'" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '           <div class="modal-dialog modal-lg">' +
    '               <div class="modal-content">' +
    '                   <div class="modal-header">' +
    '                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                       <h3>To\'quv aksessuarlar</h3>' +
    '                   </div>' +
    '                   <div class="modal-body">' +
    '                       <table id="table_toquv_acs_'+indeks+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                          <thead>' +
    '                           <tr>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul">Artikul / Kodi</th>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">Aksessuar</th>' +
    '                              <th class="list-cell__turi">' +
    '                                                            Turi                                                        </th>' +
    '                              <!--<th class="list-cell__qty">' +
    '                                                                                                                    </th>-->' +
    '                              <!--<th class="list-cell__button">' +
    '                                                            <div class="add_toquv_acs btn btn-success" data-row-index=""><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                                        </th>-->' +
    '                           </tr>' +
    '                          </thead>' +
    '                          <tbody id="tbody">' +
    '                          </tbody>' +
    '                      </table>' +
    '                  </div>' +
    '              </div>' +
    '          </div>' +
    '      </div>' +
    '  </div>';

</script>
