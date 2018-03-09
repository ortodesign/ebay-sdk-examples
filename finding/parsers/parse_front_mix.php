<?php $pagetitle = 'Парсим с newEgg';
include __DIR__ . '/../header.php'; ?>

<link rel="stylesheet" href="node_modules/jsoneditor/dist/jsoneditor.min.css">
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <h5>Входящий список json</h5>
            <div style="height: 100px;">
                <p>Default:
                    <small>jsons/serj_newEgg.json</small>
                </p>
                <input type="file" name="inJsonFileName" id="inJsonFileName" class="btn btn-info">

            </div>
            <textarea name="inJsonTextArea" id="inJsonTextArea" cols="30" rows="6" style="width: 100%"></textarea>
            <div id="inJsonEditor" class="bg-faded"></div>

        </div>
        <div class="col-sm-4">
            <h5>Вывод json</h5>
            <div id="inJsonFileName"></div>
            <div style="height: 100px;">
                <p>
                    <input type="radio" name="checkParseSource" id="checkCitilink" value="citi"><label
                            for="checkCitilink">Ситилинк</label>
                    <input type="radio" name="checkParseSource" id="checkNewEgg" value="newegg" checked><label
                            for="checkNewEgg">NewEgg</label></p>
                <button id="start" type="button" class="btn btn-danger w-75 m-auto d-block">Пуск</button>

            </div>
            <textarea name="outJsonTextArea" id="outJsonTextArea" cols="30" rows="30" style="width: 100%"></textarea>
            <input type="text" name="outJsonFilenameInput" id="outJsonFilenameInput">
            <button id="outJsonSaveButton" type="button" class="btn btn-info">Сохранить</button>
        </div>

        <div class="col-sm-4">
            <h5 style="display: inline-block;">Прокси</h5>
            <input type="checkbox" name="checkboxUseProxy" id="checkboxUseProxy" style="margin-left: 30px;"><label
                    for="checkboxUseProxy">Использовать прокси</label>

            <div style="height: 100px;justify-content: center">
                <p>Default:
                    <small>proxy/proxy.txt</small>
                </p>
                <input type="file" name="inJsonFileName" id="inJsonFileName" class="btn btn-info">

            </div>
            <textarea name="inProxyTextArea" id="inProxyTextArea" cols="30" rows="10" style="width: 100%"></textarea>
            <button type="button" class="btn btn-info" disabled>Забрать с
                <small>https://www.socks-proxy.net/</small>
            </button>
            <div class="h3">log window</div>
            <textarea name="logWindow" id="logWindow" cols="30" rows="10" style="width: 100%"></textarea>

        </div>
        <div id="point">

        </div>
    </div>
    <div class="row">
        <div id="logWindow">

        </div>
    </div>

</div>
<?php include __DIR__ . '/../jsLibs.php'; ?>
<script src="node_modules/he/he.js"></script>
<script src="node_modules/jsoneditor/dist/jsoneditor.min.js"></script>
<script>

    var parsedBufferData = [];

    var container = document.getElementById("inJsonEditor");
    var options = {
        onChange: function () {
            $('#inJsonTextArea').val(JSON.stringify(inJsonEditor.get(), null, 2));
        }
    };
    var inJsonEditor = new JSONEditor(container, options);

    var defaultInJson = <? echo file_get_contents( "jsons/tmp_serj_newEgg.json", "r" ); ?>;
    inJsonEditor.set(defaultInJson);

    // $('#inJsonTextArea').val(JSON.stringify(defaultInJson));
    $('#inJsonTextArea').val(JSON.stringify(inJsonEditor.get(), null, 2));

    document.getElementById('inJsonFileName').addEventListener('change', CopyMe, false);

    function CopyMe(evt) {
        console.log(111);
        var file = evt.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = function (event) {
                inJsonEditor.set($.parseJSON(event.target.result));
                $('#inJsonTextArea').val(JSON.stringify(inJsonEditor.get(), null, 2));
            };
        }
    }

    var outPut = $('#outJsonTextArea');

    var proxy = `<? echo file_get_contents( "proxy/proxy.txt", "r" ); ?>`;
    $('#inProxyTextArea').val(proxy);
    let proxyListIndex = 0;
    var proxyList = [];

    var proxyChecked = function () {
        return $('[name=checkboxUseProxy]:not(checked)')[0].checked;
    };

    $('[name=checkboxUseProxy]:not(checked)').on('click', function () {
        if (proxyChecked()) {
            proxyList = $('#inProxyTextArea').val().split("\n");
            proxyList = proxyList.map(function (it) {
                if (typeof (it) === 'string' && it != "") return it.trim();
            });
            proxyList = proxyList.filter(function (n) {
                return n != undefined
            });
            // console.log(proxyList);
        }
    });


    function getProxy() {
        return proxyList.shift();
        // yield proxyList.shift();
    }


    // async function
    async function fetchAsync(url = 'https://api.github.com') {
        // await response of fetch call
        let response = await fetch(url);
        // only proceed once promise is resolved
        let data = await response.json();
        // only proceed once second promise is resolved
        return data;
    }

    // trigger async function
    // log response or catch error of fetch promise
    // fetchAsync()
    //     .then(data => console.log(data))
    //     .catch(reason => console.log(reason.message))

    var logWindowText = '';
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

    // logw('dsfsds', 'sdfdsf', 'sdfsdfsd');

    $('#start').on('click', function () {
        var parseSource = $('input[name=checkParseSource]:checked').val(); // "citi" || "newegg"
        console.log(parseSource);
        console.log(proxyList);
        var inJson = inJsonEditor.get();

        if (parseSource === "newegg") {
            var Web = {
                StateManager: {
                    Cookies: {
                        get: function () {
                            return false
                        },
                        Name: {
                            NVTC: '',
                            LOGIN: ''
                        }
                    }
                }
            };

            // for (let item in inJson) {
            inJson.forEach(function (item) {

                    let somethingWrong = true;

                    console.log('parsers/php/parse_newEgg.php?' + 'target=' + item.citilinkURL + (proxyChecked() ? ('&timeout=20&proxy=' + proxyList[proxyListIndex]) : ''));

                    fetchAsync('parsers/php/parse_newEgg.php?' + 'target=' + encodeURIComponent(item.citilinkURL) + (proxyChecked() ? ('&timeout=20&proxy=' + proxyList[proxyListIndex]) : ''))
                        .then(data => {
                            // console.log(data);
                            if (data && data.page_breadcrumb) {
                                parsedBufferData.push(data);
                                outPut.val(JSON.stringify(parsedBufferData));
                                console.log(JSON.stringify(parsedBufferData))
                            } else {
                                logw(item.citilinkURL, data.data);
                                proxyListIndex++

                            }
                        })
                        .catch(reason => logw(reason.message))


                }
            );

        }

    })
    ;

    //Save parsed json to file
    $('#outJsonSaveButton').on('click', function () {
        console.log('save json to ', $('#outJsonFilenameInput').val());
        // $.ajax({
        //     method:post,
        //     url:''
        // })
    });

	<?php
	//	$spisok = file_get_contents( __DIR__ . '/jsons/serj_newEgg.json', "r" );
	?>

    //var inJson = <?php //echo $spisok; ?>;

    //fake object for eval

    // $('#point').append('[');

    // inJson.forEach(function (item) {
    //     $.ajax({
    //         // url: 'parsers/php/parse_newEgg.php?' + 'target=' + 'https://www.newegg.com/Product/Product.aspx?Item=N82E16828840014', //item.citilinkURL,,
    //         url: 'parsers/php/parse_newEgg.php?' + 'target=' + item.citilinkURL,
    //         // data: data,
    //         success: function (data) {
    //             var ev = eval('(' + data + ')');
    //             console.log(ev);
    //
    //             var outObj = {
    //                 'our_name': item.our_name,
    //                 'synonyms': item.synonyms,
    //                 'citilinkURL': item.citilinkURL,
    //                 'categoryID': null,
    //                 'picture_url': ev.picUrl,
    //                 'citilinkPrice': ev.product_sale_price,
    //                 'citilink_data': null,
    //             };
    //             $('#point').append('<div>'+JSON.stringify(outObj)+'</div>');
    //             //// $('#point').append('<div>'+ev.product_model+'</div>');
    //             // $('#point').append('<h3>' + he.decode(String(ev.product_title)) + '</h3>');
    //             // $('#point').append('<img src="' + ev.picUrl + '" style="width: 800px">');
    //             // $('#point').append('<div class="h1">' + ev.product_sale_price + '</div>');
    //         },
    //         // dataType: dataType
    //     });
    //
    // });
    // $('#point').append(']');

</script>


</body>
</html>
