	var ans = Math.floor((Math.random() * 98) + 1);
	var min = 1;
	var max = 99;
	var a  = 0;
	var i = 0;
	

	function verifyorder(y){
        

		
			if(y < 1){
			
				document.getElementById('value').value=null;
				document.getElementById('value').focus();
				return false;
				
			
			}
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
	
