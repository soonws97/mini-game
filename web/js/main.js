	var ans = Math.floor((Math.random() * 98) + 1);
	var min = 1;
	var max = 99;
	var a  = 0;
	var i = 0;

	// $(document).ready(function(){
	// 	var min = $('#min').text();
	// 	var max = $('#max').text();
	// 	var mid = $('#to').text();
	// 	min = parseInt(min);
	// 	max = parseInt(max);
	// 	val = max-min;
	// 	if ( val >90 && val<100 ){
	// 		$('.box').css({'height':'10%','width':'10%'});
	// 	} else if ( val >80 && val<90 ){
	// 		$('.box').css({'height':'20%','width':'20%'});
	// 	}else if ( val >70 && val<80 ){
	// 		$('.box').css({'height':'30%','width':'30%'});
	// 	}else if ( val >60 && val<70 ){
	// 		$('.box').css({'height':'40%','width':'40%'});
	// 	}else if ( val >50 && val<60 ){
	// 		$('.box').css({'height':'50%','width':'50%'});
	// 	}else if ( val >40 && val<50 ){
	// 		$('.box').css({'height':'60%','width':'60%'});
	// 	}else if ( val >30 && val<40 ){
	// 		$('.box').css({'height':'70%','width':'70%'});
	// 	}else if ( val >20 && val<30 ){
	// 		$('.box').css({'height':'80%','width':'80%'});
	// 	}else if ( val >10 && val<20 ){
	// 		$('.box').css({'height':'90%','width':'90%'});
	// 	}else if ( val >0 && val<10 ){
	// 		$('.box').css({'height':'95%','width':'95%'});
	// 	}else if ( $.isNumeric(mid) ){
	// 		$('.box').css({'height':'100%','width':'100%'});
	// 	}
	// })


	function verifyorder(y){



			if(y < 1){

				document.getElementById('value').value=null;
				document.getElementById('value').focus();
				return false;


			}

			document.getElementById('value').value=null;
			document.getElementById('value').focus();




				/*if(y != ans){
					//获得最大值
					a = Math.max(y,ans);


					if(a == y){

							if (parseInt(y) >= parseInt(max)){

								document.getElementById('value').value=null;
								document.getElementById('value').focus();

							}
							else{

								max = y;
								document.getElementById("min").innerHTML = min  ;
								document.getElementById("to").innerHTML = "到";
								document.getElementById("max").innerHTML = max  ;
								document.getElementById('value').value=null;
								document.getElementById("value").focus();

								i = i + 1;
								document.getElementById("time").innerHTML = "您还有 "+(6-i)+" 次机会哟！";
								break;


							}

						}
					else {

							if(parseInt(y) <= parseInt(min)){

								document.getElementById('value').value=null;
								document.getElementById('value').focus();



							}

							else{

								min = y;
								document.getElementById("min").innerHTML = min  ;
								document.getElementById("to").innerHTML = "到";
								document.getElementById("max").innerHTML = max  ;
								document.getElementById('value').value=null;
								document.getElementById("value").focus();

								i = i + 1;
								document.getElementById("time").innerHTML = "您还有 "+(6-i)+" 次机会哟！";
								break;
							}
					}

				}
				else {

					document.getElementById("time").innerHTML ="恭喜你，您就是我们要找的幸运儿！";
					document.getElementById('value').value=null;
					document.getElementById('min').innerHTML="";
					document.getElementById('to').innerHTML=ans;
					document.getElementById('max').innerHTML="";
					document.getElementById('value').removeAttribute('disabled');
					document.getElementById("btnSubmit").disabled = true;

				}

			}

			while(i >= 5);

			if(i==5)
				{
					document.getElementById("time").innerHTML ="抱歉，今天的次数已用完，请明天再来";
					document.getElementById('value').value=null;
					document.getElementById('min').innerHTML="";
					document.getElementById('to').innerHTML="";
					document.getElementById('max').innerHTML="";
					document.getElementById('value').removeAttribute('disabled');
					document.getElementById("btnSubmit").disabled = true;


				}
			*/


			$.ajax({
			   url: "index.php?r=site/key",

			   type: 'post',
			   data: {
						 record:y,
						 _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
					 },

			   success: function (data) {
				  console.log(data);
				   location.reload();

			   },
				error: function(error){
					console.log("Something went wrong", error);
				}
		  });






	}
