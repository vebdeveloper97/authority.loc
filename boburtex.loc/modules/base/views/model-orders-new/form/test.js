/*
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 30.06.20 11:36
 */

let color_pantone = (index.colorPantone)?
    '        <span style="background: rgb('+index.colorPantone.r+', '+index.colorPantone.g+', '+index.colorPantone.b+'); width: 10%;">' +
    '            <span style="opacity: 0;">' +
    '                <span class="badge">' +
    '                    r' +
    '                </span>' +
    '            </span>' +
    '        </span><span style="padding-left: 5px;"> '+index.colorPantone.code+' </span>' : '';
let color_boyoq = (index.color)?
    '        <span style="background: '+index.color.color+'; width: 10%;">' +
    '            <span style="opacity: 0;">' +
    '                <span class="badge">' +
    '                    r' +
    '                </span>' +
    '            </span>' +
    '        </span><span style="padding-left: 5px;"> '+index.color.color_id+' </span>' : '';
let plan_mato = index.toquvRawMaterials.name;
let planParent = '<div class="row planParent">' +
    '    <div class="col-md-1 color_pantone">' +color_pantone+
    '    </div>' +
    '    <div class="col-md-1 color_boyoq">' + color_boyoq +
    '    </div>' +
    '    <div class="col-md-2 plan_mato">' +
    '        <input type="text" disabled="" class="form-control" value="'+plan_mato+'" />' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-thread_length">' +
    '            <input type="text" id="moireldept-thread_length" class="number form-control" name="MoiRelDept['+key+'][child]['+num+'][thread_length]" value="'+index.thread_length+'" />' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-finish_en">' +
    '            <input type="text" id="moireldept-finish_en" class="number form-control" name="MoiRelDept['+key+'][child]['+num+'][finish_en]" value="'+index.finish_en+'" />' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-finish_gramaj">' +
    '            <input type="text" id="moireldept-finish_gramaj" class="number form-control" name="MoiRelDept['+key+'][child]['+num+'][finish_gramaj]" value="'+index.finish_gramaj+'" />' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <input type="text" disabled="" class="form-control" value="'+index.raw_fabric+'" />' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <input type="hidden" name="MoiRelDept['+key+'][child]['+num+'][model_orders_planning_id]" value="'+index.id+'" />' +
    '        <div class="form-group field-moireldept-'+key+'-raw_fabric3">' +
    '            <input type="text" id="moireldept-'+key+'-raw_fabric3" class="number required form-control" name="MoiRelDept['+key+'][child]['+num+'][quantity]" value="'+index.raw_fabric+'" />' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-'+key+'-start_date-'+num+'">' +
    '            <div id="moireldept-'+key+'-start_date-'+num+'-kvdate" class="input-group date">' +
    '                <input' +
    '                    type="text"' +
    '                    id="moireldept-'+key+'-start_date-'+num+'"' +
    '                    class="start_date required form-control krajee-datepicker"' +
    '                    name="MoiRelDept['+key+'][child]['+num+'][start_date]"' +
    '                    value="30.06.2020"' +
    '                    data-datepicker-source="moireldept-'+key+'-start_date-'+num+'-kvdate"' +
    '                    data-datepicker-type="2"' +
    '                    data-krajee-kvdatepicker="kvDatepicker_24d0503e"' +
    '                />' +
    '                <span class="input-group-addon kv-date-remove"> <i class="fa fa-times kv-dp-icon"></i> </span>' +
    '            </div>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-'+key+'-end_date-'+num+'">' +
    '            <div id="moireldept-'+key+'-end_date-'+num+'-kvdate" class="input-group date">' +
    '                <input' +
    '                    type="text"' +
    '                    id="moireldept-'+key+'-end_date-'+num+'"' +
    '                    class="end_date required form-control krajee-datepicker"' +
    '                    name="MoiRelDept['+key+'][child]['+num+'][end_date]"' +
    '                    value=""' +
    '                    data-datepicker-source="moireldept-'+key+'-end_date-'+num+'-kvdate"' +
    '                    data-datepicker-type="2"' +
    '                    data-krajee-kvdatepicker="kvDatepicker_24d0503e"' +
    '                />' +
    '                <span class="input-group-addon kv-date-remove"> <i class="fa fa-times kv-dp-icon"></i> </span>' +
    '            </div>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1">' +
    '        <div class="form-group field-moireldept-'+key+'-add_info3">' +
    '            <textarea id="moireldept-'+key+'-add_info3" class="form-control" name="MoiRelDept['+key+'][child]['+num+'][add_info]" rows="1">'+index.add_info+'</textarea>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 hidden">' +
    '        <button type="button" class="btn btn-success btn-xs copyButton" data-num="3" data-key="1"><i class="fa fa-plus"></i></button>' +
    '    </div>' +
    '</div>';