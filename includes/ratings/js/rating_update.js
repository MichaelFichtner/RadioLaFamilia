/*-------------------------------------------------------+
| Dynamic Star Rating Redux
| Developed by Jordan Boesch
| www.boedesign.com
| Licensed under Creative Commons - 
| http://creativecommons.org/licenses/by-nc-nd/2.5/ca/
| Used CSS from komodomedia.com.
+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
// PRE-LOAD IMAGES ----------

if (document.images){
  pic1 = new Image(220,19); 
  pic1.src = "/images/ratings/rating_loading.gif"; 

  pic2 = new Image(25,75); 
  pic2.src = "/images/ratings/rating_star.gif"; 

  pic3 = new Image(25,75); 
  pic3.src = "/images/ratings/rating_star_2.gif"; 
  
  pic4 = new Image(16,13); 
  pic4.src = "/images/ratings/rating_tick.gif";
  
  pic5 = new Image(14,14); 
  pic5.src = "/images/ratings/rating_warning.gif";
}

// AJAX ---------------------

var xmlHttp

function GetXmlHttpObject(){

var xmlHttp = null;

	try {
	  // Firefox, Opera 8.0+, Safari
	  xmlHttp = new XMLHttpRequest();
	  }
	catch (e) {
	  // Internet Explorer
	  try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
	  catch (e){
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	  }
	  
	return xmlHttp;

}

// Calculate the rating
function rate(rating,id,show5,showPerc,showVotes,rtype,ratedir){

	xmlHttp = GetXmlHttpObject()
	
	if(xmlHttp == null){
		alert ("Your browser does not support AJAX!");
		return;
	  }

	xmlHttp.onreadystatechange = function(){
		
	var loader = document.getElementById('loading_'+id);
	var uldiv = document.getElementById('ul_'+id);
	
		if (xmlHttp.readyState == 4){ 
			
			//loader.style.display = 'none';
			var res = xmlHttp.responseText;
			
			//alert(res);
			
			if(res == 'already_voted'){
				
				loader.style.display = 'block';
				loader.innerHTML = '<div class="voted_twice">You have already rated this!</div>';
				
			} else {
				
				loader.style.display = 'block';
				loader.innerHTML = '<div class="voted">Thank you for your Rating!</div>';

				if(show5 == true){
					var out = document.getElementById('outOfFive_'+id);
					var calculate = parseInt(res)/20;
					out.innerHTML = Math.round(calculate*100)/100; // 3.47;
					//out.innerHTML = Math.round((calculate*2),0)/2; // 3.5;
				} 
				
				if(showPerc == true){
					var perc = document.getElementById('percentage_'+id);
					//var newPerc = Math.round(Math.ceil(res/5))*5;
					var newPerc = parseInt(res);
					perc.innerHTML = newPerc+'%';
				}
				
				else if(showPerc == false){
					var newPerc = res;
				}
				
				if(showVotes == true){
					var votediv = document.getElementById('showvotes_'+id).firstChild.nodeValue;
					var splitted = votediv.split(' ');
					var newval = parseInt(splitted[0]) + 1;
					if(newval == 1) {
						document.getElementById('showvotes_'+id).innerHTML = 'Total: '+newval+' rate';
					} else if(newval < 5) {
						document.getElementById('showvotes_'+id).innerHTML = 'Total: '+newval+' ratings';
					} else {
						document.getElementById('showvotes_'+id).innerHTML = 'Total: '+newval+' ratings';
					}
				}
				
				var ulRater = document.getElementById('rater_'+id);
				ulRater.className = 'star-rating2';
				
				var all_li = ulRater.getElementsByTagName('li');
				
				// start at 1 because the first li isn't a star
				for(var i=1;i<all_li.length;i++){
					
					all_li[i].getElementsByTagName('a')[0].onclick = 'return false;';
					all_li[i].getElementsByTagName('a')[0].setAttribute('href','#');
					
				}
				
				if(navigator.appName == 'Microsoft Internet Explorer'){
					uldiv.style.setAttribute('width',newPerc+'%'); // IE
				 } else {
					uldiv.setAttribute('style','width:'+newPerc+'%'); // Everyone else
				 }
				
			}
		} else {
			loader.innerHTML = '<img src="/images/ratings/rating_loading.gif" alt="loading" />';	
		}
	
	}
	var url = ""+ratedir+"ratings_type_stars_process.php";
	var params = "id="+id+"&rating="+rating+"&rtype="+rtype;
	xmlHttp.open("POST",url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send(params);

} 