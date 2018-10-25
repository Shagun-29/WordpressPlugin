function hiding(){
document.getElementById("wpfooter").style.display="none";
}


var showForm=true;
var usedApi="";

// for new variables
var id_used="";
var url1,url2,url3;
var short_url_used;

function copy(){

  var copyText = document.getElementById("text-inside");
  copyText.setAttribute('readonly', '');
  // var copyText = document.querySelector('#modal-content');
  copyText.select();
  document.execCommand("copy");
  document.getElementById("click-copy-text").classList.remove('hide');
  
}



function viewDetails(id,calci_url,short_url){
  document.getElementById("click-copy-text").classList.add('hide');
  id_used=id;
  document.getElementById('myModal').style.display = "block";
  // var id="5b9ab036a6aaea5e7f2481ac";
  url=calci_url;
  ex=url.split("?");
  
  url2=ex[0]+"?vHeight=1";
  url3=ex[0]+"?q=1";

  short_url_used=short_url;
  document.getElementById("cat01").classList.add('active-span');
  document.getElementById("cat02").classList.remove('active-span');
  document.getElementById("cat03").classList.remove('active-span');

  document.getElementById('text-inside').innerText='[outgrow id="'+ id + '" data_url="'+ url +'" short_url="'+short_url+'"][/outgrow]';
  // document.getElementById('text-inside').innerText="<div><div class='op-interactive' id='"+ id +"' data-url='"+ url +"' data-surl='"+short_url+"' data-width='100%'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/sloader.js'></script><script>initIframe('"+id+"');</script></div>";
}

window.onclick = function(event) {
  var modal=this.document.getElementById('myModal')
  if (event.target == modal) {
      modal.style.display = "none";
  }
}

function hide(){
  document.getElementById("myModal").style.display = "none";
}

function hideFooter(){
  document.getElementById("wpfooter").style.display="none";
}

// setTimeout(() => {
//   document.getElementById('wpfooter').style.display="none";
// }, 10);

function cat01(){
  document.getElementById("cat01").classList.add('active-span');
  document.getElementById("cat02").classList.remove('active-span');
  document.getElementById("cat03").classList.remove('active-span');
  document.getElementById("click-copy-text").classList.add('hide');
  document.getElementById('text-inside').innerText='[outgrow type="mobile_full_screen" id="'+ id_used + '" data_url="'+ url +'" short_url="'+short_url_used+'"][/outgrow]';
  // document.getElementById('text-inside').innerText="<div><div class='op-interactive' id='"+ id_used +"' data-url='"+ url +"' data-surl='"+short_url_used+"' data-width='100%'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/sloader.js'></script><script>initIframe('"+id_used+"');</script></div>";

}
function cat02(){
  document.getElementById("cat01").classList.remove('active-span');
  document.getElementById("cat02").classList.add('active-span');
  document.getElementById("cat03").classList.remove('active-span');
  document.getElementById("click-copy-text").classList.add('hide');
  document.getElementById('text-inside').innerText='[outgrow type="mobile_in_page" id="'+ id_used + '" data_url="'+ url2 +'" short_url="'+short_url_used+'"][/outgrow]';
  // document.getElementById('text-inside').innerText="<div><div class='op-interactive' id='"+ id_used +"' data-url='"+ url2 +"' data-surl='"+short_url_used+"' data-width='100%'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/nloader.js'></script><script>initIframe('"+id_used+"');</script></div>";

}
function cat03(){
  document.getElementById("cat01").classList.remove('active-span');
  document.getElementById("cat02").classList.remove('active-span');
  document.getElementById("cat03").classList.add('active-span');
  document.getElementById("click-copy-text").classList.add('hide');
  document.getElementById('text-inside').innerText='[outgrow type="pop_up" id="'+ id_used + '" data_url="'+ url3 +'" ][/outgrow]';
  // document.getElementById('text-inside').innerText="<div><div id='"+ id_used +"' data-embedCookieDays='10' data-embedScheduling='false' data-embedTimed='true' data-embedExit='false' data-embedTimeFormat='0' data-embedTimeValue='5' data-embedBorderRadius='0' data-embedFontSize='12' data-textcolor='#fb5f66' data-bgcolor='#fb5f66' data-prop='outgrow-p' data-type='outgrow-l'  data-url='"+ url3 +"' data-text='Get Started'></div><script src='https://dyv6f9ner1ir9.cloudfront.net/assets/js/nloader.js'></script><script>initIframe('"+id_used+"');</script></div>";
}

function result(type){
  document.getElementById("click-copy-text").classList.add('hide');
  if(type == 'Calculator'){
    
    // document.getElementById("Calculator").classList.add('active-span');
    // document.getElementById("Quiz").classList.remove('active-span');
    // document.getElementById("Poll").classList.remove('active-span');
    document.getElementById("result1").style.display="block";
    document.getElementById("result2").style.display="none";
    document.getElementById("result3").style.display="none";
  }
  else if(type == 'Quiz'){
    // document.getElementById("Quiz").classList.add('active-span');
    // document.getElementById("Calculator").classList.remove('active-span');
    // document.getElementById("Poll").classList.remove('active-span');
    document.getElementById("result1").style.display="none";
    document.getElementById("result2").style.display="block";
    document.getElementById("result3").style.display="none";
  }
  else if(type == 'Poll'){
    // document.getElementById("Poll").classList.add('active-span');
    // document.getElementById("Quiz").classList.remove('active-span');
    // document.getElementById("Calculator").classList.remove('active-span');
    document.getElementById("result1").style.display="none";
    document.getElementById("result2").style.display="none";
    document.getElementById("result3").style.display="block";
  }
  
}

function displayGo(){
  document.getElementById('final-outgrow').style.display="none";
}

function addSection(){
  document.getElementById('main-section').style.display="block";
}

function apiChange(){
  console.log('================------------Change Method-------------=============');
  val=document.getElementById('select-id').value;
  console.log(':: The value is ::',val);

  }