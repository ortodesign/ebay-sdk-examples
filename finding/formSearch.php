
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
	<title>Document</title>
</head>
<body>

<div class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card text-white p-5 bg-primary">
					<div class="card-body">
						<h1 class="mb-4">search form</h1>
						<form action="/" id="search_ebay" method="get">
							<div class="form-group">
								<label>Название товара</label>
								<input id="name" name="name" type="text" class="form-control" placeholder="введите название"
								       value="iPhone 7">
							</div>
							<div class="form-group d-flex">
								<div class="col-sm-6 row">
									<label class="col-sm-6 col-form-label">мин цена</label>
									<div class="col-sm-6">
										<input id="price_min" name="price_min" type="text" class="form-control" placeholder="" value="1">
									</div>
								</div>
								<div class="col-sm-6 row">
									<label class="col-sm-6 col-form-label">макс цена</label>
									<div class="col-sm-6">
										<input id="price_max" name="price_max" type="text" class="form-control" placeholder="" value="10">
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-secondary">Искать</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div id="resp">

	</div>
</div>
<style>
	#resp{
		/*position: fixed;*/
		/*height: 200px;*/
		/*width: 80%;*/
		/*background: gray;*/
		/*z-index: -1;*/
	}
</style>
<script
	src="http://code.jquery.com/jquery-3.2.1.min.js"
	integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $("form#search_ebay").submit(function(event) {
            event.preventDefault();
            var values = [];
            $("input").each(function() {
                values[$(this).attr("id")] = $(this).val();
            });
            console.log(values);
            var input = $("#name");
            // var inputs = {
            //     name : $("input#name).val,
            //     price_min : $("input#price_min).val,
            //     price_max : $("input#price_max).val
            // };

            $.ajax({
                type: "POST",
                data     : {data:values},
                url: "YS_05-find-items-advanced.php",
                success: function(data) {
                    // debugger;
                    // input.val(data);
                    $('#resp').html(data);
                }
            });
        });
    });
</script>
</body>
</html>
