<?php
/**
 * Created by PhpStorm.
 * User: Uzver
 * Date: 26.01.2018
 * Time: 11:21
 */
require_once( 'functions.php' );
$dollar = getDollarCourse();
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="node_modules/jsoneditor/dist/jsoneditor.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.bootstrap4.min.css">

<body>

<?php include 'menu.php'; ?>
<img src="loading2.gif" alt="" class="loader">
<div class="ajaxResultWindow hidden"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" id="switchDebug" class="btn btn-warning">^</button>
            <div id="events">

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-12">
            <h5 id="dollar" class="text-right"></h5>
            <hr>
            <h3>Спаршено с сити
                <small>- большая куча</small>
            </h3>
            <button class="btn btn-danger" id="delSelected">delete selected</button>
            <button class="btn btn-warning" id="parseSelToEbay">parse selected from Ebay</button>
            <div style="width: 100%">
                <table id="citiTable" class="display table table-striped table-hover table-inverse"
                       cellspacing="0"
                       width="100%">
                    <!--Таблица конфигурируется в js-->

                </table>
            </div>
            <hr>
            <!--            <hr>-->
            <!--            <h3>Таймлайн eBay-->
            <!--                <small>- показаны самые близкие к окончанию аукциона лоты из группы связанной с нашим названием</small>-->
            <!--            </h3>-->
            <!--            <div style="width: 100%">-->
            <!--                <table id="timelineEbayTable" class="display table table-striped table-hover table-inverse"-->
            <!--                       cellspacing="0"-->
            <!--                       width="100%">-->
            <!--                    <!--Таблица конфигурируется в js-->
            <!---->
            <!--                </table>-->
            <!--            </div>-->
            <!--            <hr>-->

            <form id="formaddCol" action="" data-id="" class="form-inline">
                <label for="addCol">Ссылка на <br>ситилинк &nbsp;</label>
                <input type="text" name="addCol" id="addCol" class="form-control">
                <button type="submit" form="formaddCol" value="Submit" class="btn btn-primary">Искать</button>
                <div class="pl-4">
                    <button type="button" form="addManualy" class="btn btn-primary">Добавить позицию вручную (in
                        progress)
                    </button>
                </div>
                <div class="pl-4">
                    <button type="button" form="addFromJson" class="btn btn-primary">Спарсить
                        <small>json список с ситилинка <br> (парсит из файла spisok.json) <br> (Формат:
                            our_name,citilinkURL,synonyms)
                        </small>
                    </button>
                </div>
                <div class="pl-4">
                    <button type="button" id="autoBDfromEbay" class="btn btn-primary">Запустить автозаполнение eBay
                    </button>
                </div>

            </form>


            <form id="formaddColEdited" action="" data-id="" class="collapse width">
                <div id="resultCity" class="wrapword p-3">

                </div>
                <button type="button" class="btn btn-secondary close closeabs"
                        onclick="$(this).parent().collapse('hide');">&times;
                </button>
            </form>
            <div id="ebayResults" class="collapse width p-3">

                <h5>Результаты поиска</h5>
            </div>
            <hr>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="openSource" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formSaveBD" action="" data-id="">
                <h5 class="modal-title">Редактируем источник</h5>

                <div class="text-right px-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<!--                    <button type="submit" form="formSaveBD" value="Submit" class="btn btn-primary saveJson">Save changes</button>-->
                    <button type="button" class="btn btn-primary saveJson">Save changes</button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Редактирование полей</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="openSourceContent">
                    <hr>
                    <div id="inJsonEditor" class="bg-faded"></div>
                    <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<!--                    <button type="submit" form="formSaveBD" value="Submit" class="btn btn-primary">Save changes</button>-->
                    <button type="button" class="btn btn-primary saveJson">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="resp">

</div>

<?php include 'jsLibs.php'; ?>
<script src="node_modules/he/he.js"></script>
<script src="node_modules/jsoneditor/dist/jsoneditor.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.bootstrap4.min.js"></script>
<script>
    var dollar = <?php echo( $dollar );?>;
    $('#dollar').html('Курс доллара: ' + dollar + ' руб.');

    function scrollTo(sel) {
        $('html, body').animate({
            scrollTop: sel.offset().top
        }, 500);
    }

    $(document).ready(function () {

        // $.fn.dataTable.ext.errMode = 'none'; //Выключить алерт ошибок таблиц

        // var saveToEBD = new $.Deferred();
        function saveToEBD(senddata) {
            var dfd = new $.Deferred();
            $.ajax({
                type: "POST",
                data: {data: senddata},
                url: "aj_update_desrc_pic_eBD.php",
                success: function (data) {
                    dfd.resolve(data);
                },
            });
            return dfd.promise();
        }

        function getJsonCitiByID(id) {
            var dfd = new $.Deferred();
            $.ajax({
                // type: "POST",
                // data: {data: senddata},
                url: "aj_Get_BD_citiSource__2.php?id=" + id,
                success: function (data) {
                    dfd.resolve(data);
                },
            });
            return dfd.promise();
        }


        var citiTable;
        var columnsCitiTable = [
            {
                "width": "40px",
                "title": "id",
                "data": null,
                "render": function (data) {
                    return data.id;
                }
            },
            {
                "width": "40px",
                "title": "categoryid",
                "data": null,
                "render": function (data) {
                    return data.categoryid;
                }
            },
            {
                // "width": "20px",
                "title": "categoryname",
                "data": null,
                "render": function (data) {
                    return data.categoryname;
                }
            },
            {
                "width": "60px",
                "title": "url",
                "data": null,
                "render": function (data) {
                    return `<a href="${data.url}" target="_blank">url citilink</a>`;
                }
            },
            {
                "width": "50px",
                "title": "price (руб)",
                "data": null,
                "render": function (data) {
                    return data.price;
                }
            },
            {
                // "width": "20px",
                "title": "brandname",
                "data": null,
                "render": function (data) {
                    return data.brandname;
                }
            },
            {
                // "width": "20px",
                "title": "shortname",
                "data": null,
                "render": function (data) {
                    return data.shortname;
                }
            },
            {
                // "width": "20px",
                "title": "non_cyr",
                "data": null,
                "render": function (data) {
                    return data.non_cyr;
                }
            },
            {
                // "width": "20px",
                "title": "pre_syn",
                "data": null,
                // "render": function (data) {
                //     let d = data.pre_syn.split(',');
                //     let s = '<div class="synons">';
                //     if (d.length > 1) {
                //         d.forEach(function (i) {
                //             s += `<span class="badge badge-info">${i}</span>`
                //         });
                //     } else {
                //         s += d[0]
                //     }
                //     return s+'</div>';
                // },
                "render": function (data) {
                    let d = data.pre_syn.split(',');
                    let s = '<div class="synons">';
                    if (d.length > 1) {
                        d.forEach(function (i) {
                            s += `<span class="badge badge-info">${i}</span>`
                        });
                    } else {
                        s += d[0]
                    }
                    return `<div class="synons">${data.pre_syn}</div>`;
                },
            },
            {
                // "width": "20px",
                "title": "EDIT",
                "data": null,
                "render": function (data) {
                    return `<button class="btn btn-primary btn-sm editButton">edit(all)</button>`;
                }
            }
        ];

        var buttons = [
            {
                text: 'Get selected data',
                action: function () {
                    // var count = citiTable.rows( { selected: true } ).count();
                    // events.prepend( '<div>'+count+' row(s) selected</div>' );

                    var rowData = citiTable.rows( { selected: true } ).data().toArray();
                    events.html( '<div>'+JSON.stringify( rowData )+'</div>' );
                }
            },
            {
                text: 'SaveBD',
                action: function () {
                    // var count = citiTable.rows( { selected: true } ).count();
                    // events.prepend( '<div>'+count+' row(s) selected</div>' );
                    var rowData = citiTable.rows( { selected: true } ).data().toArray();
                    // events.html( '<div>'+JSON.stringify( rowData )+'</div>' );
                    saveBDciti(rowData);
                }
            },
            {
                text: 'Parse selected from Ebay',
                action: function () {
                        console.log('removing');
                        var ids = [];
                        var idsobj = citiTable.rows( { selected: true } ).ids();
                        $.each(idsobj, function(index, value) {
                            console.log(index, value);
                            ids.push(value);
                        });
                        $.ajax({
                            type: "POST",
                            data: {ids: ids},
                            url: "aj_Get_ebay_autoToBD__2.php?part=true",
                            beforeSend: function () {
                                $('.loader').show();
                            },
                            success: function (data) {
                                console.log(data);
                                ids = [];
                                $('.loader').hide();
                            },
                        });
                        // citiTable.rows( { selected: true } )
                        //     .remove()
                        //     .draw();
                }
            },
        ];


        // var columnsTimelineEbayTable = [
        //     {
        //         "width": "20px",
        //         "title": "наш id",
        //         "data": null,
        //         "render": function (data, type, row, meta) {
        //             // return data.product_id + ' | ' + data.ebaydata.itemId;
        //             // console.log( type,row,meta);
        //             return '<span class="badge ancorScrollOurID ' + (data.description ? 'badge-success">' : 'badge-danger">') + data.product_id + '</span><input type="hidden" class="inputEID" value="' + data.ebaydata.itemId + '">';
        //         }
        //     },
        //     {
        //         "width": "50px",
        //         "title": "Время окончания",
        //         "data": "datetimeleft",
        //         "render": function (data, type, row, meta) {
        //             // console.log(type);
        //             // console.log(row);
        //             // console.log(meta);
        //             return moment(data, moment.ISO_8601, 'ru').format("MM-DD HH:mm");
        //         }
        //     },
        //     {
        //         "title": "Категория ebay",
        //         "data": "ebaydata.primaryCategory.categoryName"
        //     },
        //     {
        //         "title": "Цена отсчета (ситилинк)",
        //         "width": "50px",
        //         "data": null,
        //         "render": function (data, type, row, meta) {
        //             return `<div>${Math.ceil(data.price / dollar)}</div>`
        //         }
        //     },
        //     {
        //         "title": "Цена ebay",
        //         "width": "50px",
        //         "data": "ebaydata.sellingStatus.currentPrice.value"
        //     },
        //     {
        //         "title": "Наша цена",
        //         "width": "50px",
        //         "data": null,
        //         // "data": "ebaydata.sellingStatus.currentPrice.value"
        //         "render": function (data, type, row, meta) {
        //             return '<input class="inputOurPrice" style="width: 100%" type="text" value="' + (data.our_price ? data.our_price : data.ebaydata.sellingStatus.currentPrice.value) + '">'
        //         }
        //     },
        //     {
        //         "title": "Наше описание",
        //         "data": null,
        //         "width": "400px",
        //         "render": function (data, type, row, meta) {
        //             if (data.description) {
        //                 // return data
        //                 return data.description + '<br><br><button class="buttonDescr btn btn-success btn-sm" style="right: 15px;width: 100%;">редактировать</button>' +
        //
        //                     '<div class="inputDescrWrapper hidden">' +
        //                     '<textarea class="inputDescr" placeholder="Заполните описание" style="right: 15px;width: 100%;">' + data.description + '</textarea>' +
        //                     '<button class="buttonDescrSave"  style="right: 15px;width: 100%;">Сохранить</button>' +
        //                     '</div>';
        //             }
        //             // else if (!data) return '<textarea class="inputDescr" placeholder="Заполните описание" style="right: 15px;width: 100%;"></textarea>';
        //             else if (!data.description) return '<button class="buttonDescr btn btn-danger btn-sm" style="right: 15px;width: 100%;">Заполнить описание</button>' +
        //                 '<div class="inputDescrWrapper hidden">' +
        //                 '<textarea class="inputDescr" placeholder="Заполните описание" style="right: 15px;width: 100%;">' + data.ebaydata.title + '</textarea>' +
        //                 '<button class="buttonDescrSave"  style="right: 15px;width: 100%;">Сохранить</button>' +
        //                 '</div>';
        //         }
        //     },
        //     {
        //         "title": "Картинка",
        //         "data": null,
        //         render: function (data, type, row, meta) {
        //             // return '<img src="' + (data.picture_url ? data.picture_url : (data.pic_url ? data.pic_url : (data.citilink_data.productPictureUrl ? data.citilink_data.productPictureUrl : data.ebaydata.galleryURL))) + '" height="70">';
        //             return '<img src="' + (data.picture_url ? data.picture_url : (data.pic_url ? data.pic_url : data.ebaydata.galleryURL)) + '" height="70">';
        //         }
        //     },
        //     {
        //         "title": "Название и ссылка(…) ebay",
        //         "data": "ebaydata",
        //         render: function (data, type, row, meta) {
        //             return `${data.title} <a href="${data.viewItemURL}" target="_blank">…</a>`;
        //         }
        //     },
        //     // {
        //     //     "title": "Наше название",
        //     //     "data": "our_name"
        //     // },
        //     {
        //         "title": "Название ситилинка",
        //         "data": null,
        //         render: function (data, type, row, meta) {
        //             return data.shortname ? data.shortname : 'Error';
        //         }
        //     },
        //     {
        //         "title": "Синонимы",
        //         "data": "pre_syn"
        //     },
        //
        //
        //     {
        //         "title": "Результаты выдачи",
        //         "data": null,
        //         render: function (data, type, row, meta) {
        //             return '<button class="runIt btn btn-outline-info btn-sm">' + data.last_approve_ebay_count + ' / ' + data.last_all_ebay_count + '</button>';
        //         }
        //     },
        //     {
        //         "title": "Минимальный и максимальный % (по дефолту 50/80)",
        //         "data": null,
        //         render: function (data, type, row, meta) {
        //             return (data.min_procent ? '<span class="badge badge-primary">' + data.min_procent + '</span> / ' : '') + (data.max_procent ? '<span class="badge badge-primary">' + data.max_procent + '</span>' : '');
        //         }
        //     }, {
        //         "title": "(chexbox = автоматом) Отправить в торги",
        //         "data": null,
        //         render: function (data, type, row, meta) {
        //             return '<input type="checkbox" class="form-check-input" ' + ((data.approved == 1) ? 'checked' : '') + '><button type="button" class="btn btn-primary btn-sm">&rarr; (in progress)</button>';
        //         }
        //     },
        // ];
        // var timelineEbayTable = $('#timelineEbayTable').DataTable({
        //     "paging": false,
        //     "ordering": false,
        //     "autoWidth": false,
        //     "ajax": {
        //         url: "aj_Get_BD_ebay__2.php",
        //         dataSrc: ''
        //     },
        //     rowId: 'ebaydata.itemId',
        //     "columns": columnsTimelineEbayTable
        // });
        var events = $('#events');

        citiTable = $('#citiTable').DataTable({
            "paging": false,
            // "ordering": false,
            // select: {
            //     style: 'multi'
            // },
            select: true,
            dom: 'Bfrtip',
            buttons: buttons,
            "autoWidth": false,
            "ajax": {
                url: "aj_Get_BD_citiSource__2.php",
                dataSrc: ''
            },
            rowId: 'id',
            "columns": columnsCitiTable,
            // "drawCallback": function( settings ) {
            // },
            // "createdRow": function ( row, data, index ) {
            // }
        });

        function saveBDciti(senddata) {
            console.log(senddata);
            $.ajax({
                type: "POST",
                data: {data: senddata},
                url: "aj_Set_BDciti__2.php",
                success: function (data) {
                    $('#events').html('<div>' + data + '</div>');
                    console.log(data);
                    // refreshMainTable();
                    citiTable.ajax.reload();
                },
            });
        }

        // citiTable.on( 'draw', function () {
        //     console.log('after draw2');
        //     $('#delSelected').css({
        //         position: 'fixed',
        //         bottom: '50px',
        //         left: '50px'
        //     });
        // } );

        // citiTable.on( 'select', function ( e, dt, type, indexes ) {
        //     // var rowData = citiTable.rows( indexes ).data().toArray();
        //     var rowData = citiTable.rows( { selected: true } ).data().toArray();
        //     events.html( '<div>'+JSON.stringify( rowData )+'</div>' );
        // } );

        $('#delSelected').on('click',function () {
            console.log('removing');
            var ids = [];
            var idsobj = citiTable.rows( { selected: true } ).ids();
            $.each(idsobj, function(index, value) {
                console.log(index, value);
                ids.push(value);
            });
            $.ajax({
                type: "POST",
                data: {ids: ids},
                url: "aj_Set_BDciti__2.php?delete=ids",
                success: function (ddata) {
                    console.log(ddata);
                    ids = [];
                },
            });
            citiTable.rows( { selected: true } )
                .remove()
                .draw();
        });

        $('#parseSelToEbay').on('click',function () {
                console.log('removing');
                var ids = [];
                var idsobj = citiTable.rows( { selected: true } ).ids();
                $.each(idsobj, function(index, value) {
                    console.log(index, value);
                    ids.push(value);
                });
                $.ajax({
                    type: "POST",
                    data: {ids: ids},
                    url: "aj_Get_ebay_autoToBD__2.php?part=true",
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success: function (data) {
                        console.log(data);
                        ids = [];
                        $('.loader').hide();
                    },
                });
            }
        );

        $('#citiTable tbody').on('click', 'tr', function (e) {
            var id = this.id;
            console.log(id);
            var tg = $(e.target);

            if (tg.hasClass('editButton')) {
                e.stopPropagation();
                $('#openSource').modal();
                var container = document.getElementById("inJsonEditor");
                var options = {
                    // onChange: function () {
                    //     $('#inJsonTextArea').val(JSON.stringify(inJsonEditor.get(), null, 2));
                    // }
                };
                var inJsonEditor = new JSONEditor(container, options);
                //var defaultInJson = <?// echo file_get_contents( "jsons/tmp_serj_newEgg.json", "r" ); ?>//;
                $.ajax({
                    // type: "POST",
                    // data: {data: senddata},
                    url: "aj_Get_BD_citiSource__2.php?id=" + id,
                    success: function (data) {
                        console.log(data);
                        inJsonEditor.set($.parseJSON(data)[0]);
                    },
                });

                $('.saveJson').on('click', function (eve) {
                    eve.preventDefault();
                    console.log([inJsonEditor.get()]);
                    saveBDciti([inJsonEditor.get()]);
                })
                // console.log(getJsonCitiByID(this.id));
                // inJsonEditor.set($.parseJSON(getJsonCitiByID(this.id)));

            } else if (tg.hasClass('synons')){
                e.stopPropagation();
                console.log('synons id ='+ id );


                var inp = $('<input class="changeSynInp" type="text" value="' + tg.text() + '">');
                if (!(tg.parent().find('input').hasClass('changeSynInp'))) {
                    inp.insertAfter(tg).focus().keyup(function (ev) {
                        if (ev.which === 13) { // or keyCode
                            //console.log('enter save to BD');
                            //do ajax stuff
                            console.log(saveBDciti([{
                                'id': id,
                                'pre_syn': inp.val()
                            }]));
                            inp.remove();
                            citiTable.ajax.reload().draw();

                            // startRefresh();
                        }
                        if (ev.which === 27) {
                            //console.log('escape CANCEL');
                            inp.remove();
                            // startRefresh();
                        }
                    });

                }


            } else {
                // if ( $(this).hasClass('selected') ) {
                //     $(this).removeClass('selected');
                // }
                // else {
                //     citiTable.$('tr.selected').removeClass('selected');
                //     $(this).addClass('selected');
                // }
            }
        });

        let parsedBufferData = []

        async function fetchAsync(url) {
            // await response of fetch call
            let response = await fetch(url);
            // only proceed once promise is resolved
            let data = await response.json();
            // only proceed once second promise is resolved
            return data;
        }


        let logWindowText = '';

        function logw() {
            var ar = Array.prototype.slice.call(arguments, 0);
            console.log(ar);
            if (ar) {
                ar.forEach(function (i) {
                    logWindowText += i + '\n'
                });
                $('#logWindow').val(logWindowText);
            }
        }


        $("#openSource").on('opensource', function (e, d) {
            console.log(d.id);

            // fetchAsync('aj_Get_BD_citiSource__2.php')
            //     .then(data => {
            //         // console.log(data);
            //         if (data) {
            //             parsedBufferData.push(data);
            if (!citiTable) {
                citiTable = $('#citiTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "autoWidth": false,
                    "ajax": {
                        url: 'aj_Get_BD_citiSource__2.php?id=' + d.id,
                        dataSrc: null
                    },
                    rowId: 'id',
                    "columns": columnsCitiTable
                });
            } else {
                citiTable.ajax.url('aj_Get_BD_citiSource__2.php?id=' + d.id).load();
            }

            // $("#openSource #openSourceContent").html(JSON.stringify(parsedBufferData));
            // console.log(JSON.stringify(parsedBufferData))
            //     } else {
            //         logw(' errE ');
            //
            //     }
            // })
            // .catch(reason => logw(reason.message));


//                 $("#openSource #openSourceContent").html(`
// <h5>${d.id}</h5>
//                 `)
        });


        // setTimeout(function () {
        //     $('textarea.inputDescr').each(function () {
        //         console.log('--*--');
        //         $(this).trumbowyg();
        //     })
        // }, 2000);


        var refreshTable;

        function startRefresh() {
            refreshTable = setInterval(function () {
                console.log('timelineEbayTable redraw');
                timelineEbayTable.ajax.reload();
            }, 2000);
        }


        // startRefresh();


        //        Обрабатываем клик по таблице таймлайн ебея
        $('#timelineEbayTable tbody').on('click keyup', function (e) {

            var tg = $(e.target);
            var ourID = tg.closest('tr').find('td span').eq(0).text(); //в первой колонке должен быть наш id
            var eID = tg.closest('tr').find('td input').eq(0).val(); //рядом с нашим ид - скрытый инпут с ебеем


            // if (tg.is('img')) {  //Добавляем инпут выбора картинки (по enter сохраняет в БД)
            //     $('#timelineEbayTable tbody input.changePicURL').remove();
            //     clearInterval(refreshTable);
            //     console.log(ourID, eID);
            //     var inp = $('<input class="changePicURL" type="text" value="' + tg.attr('src') + '">');
            //     if (!($(e.target).parent().find('input').hasClass('changePicURL'))) {
            //         inp.insertAfter(tg).focus().keyup(function (ev) {
            //             if (ev.which === 13) { // or keyCode
            //                 //console.log('enter save to BD');
            //                 //do ajax stuff
            //                 console.log(saveToEBD({
            //                     'id': eID,
            //                     'pic_url': inp.val()
            //                 }));
            //                 inp.remove();
            //                 startRefresh();
            //             }
            //             if (ev.which === 27) {
            //                 //console.log('escape CANCEL');
            //                 inp.remove();
            //                 startRefresh();
            //             }
            //         });
            //     }
            // }

            // if (tg.is('button.buttonDescr')) {         //Открываем редактирование описания
            //     clearInterval(refreshTable);
            //     var inp = tg.closest('td').find('div.inputDescrWrapper');
            //     inp.toggleClass('hidden');
            //     inp.find('textarea').trumbowyg({
            //         lang: 'ru',
            //         // autogrow: true
            //     });
            // }
            //
            // if (tg.is('button.buttonDescrSave')) {         // сохраняет в БД описание
            //     var inp = tg.closest('td').find('div.inputDescrWrapper');
            //     var tArea = tg.closest('td').find('div.trumbowyg-editor');
            //     inp.toggleClass('hidden');
            //     console.log(saveToEBD({
            //         'id': eID,
            //         'description': tArea.html()
            //     }));
            //     // console.log(tArea.html());
            //     scrollTo(tg.closest('tr'));
            //     startRefresh();
            // }
            //
            //
            // if (tg.is('span.ancorScrollOurID')) {         // скролл к продукту-группе (наш айди)
            //     $("#openSource").modal().trigger('opensource', [{id: ourID}]);
            //
            //     // scrollTo($("tr[data-id='" + ourID + "']").eq(0));
            // }
            //
            // if (tg.is('input.inputOurPrice')) {         // сохраняет в БД цену
            //     console.log(e);
            //     var inp = $(e.target);
            //     if (e.which === 13) {
            //         console.log(saveToEBD({
            //             'id': eID,
            //             'our_price': inp.val()
            //         }));
            //         // inp.remove();
            //         startRefresh();
            //     }
            //     if (e.which === 27) {
            //         //console.log('escape CANCEL');
            //         // inp.remove();
            //         startRefresh();
            //     }
            // }
        });


    });

    $('#switchDebug').click(function () { //Прячем дебаг
        $('#events').toggleClass('hidden')
    });
    //Автозаполнение с eBay аяксом
    $('#autoBDfromEbay').on('click', function (e) {
        console.log('Автозаполнение с eBay аяксом');
        e.preventDefault();
        $.ajax({
            url: "aj_Get_ebay_autoToBD__2.php",
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (data) {
                $('.loader').hide();
                $('.ajaxResultWindow').removeClass('hidden').html(data);
                setTimeout(function () {
                    $('.ajaxResultWindow').addClass('hidden');
                }, 2000)
            }
        });
    })
</script>

</body>
</html>
