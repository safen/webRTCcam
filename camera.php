<?php
session_start();
?>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <script type="text/javascript">
	    var host = window.location.host;
	    if ((host == window.location.host) && (window.location.protocol != "http:"))
	        window.location.protocol = "http";
	</script>
        <link rel="stylesheet" type="text/css" href="st.css" />
    <link rel="stylesheet" type="text/css" href="stylesheets/style.css" />
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.3.0/css/font-awesome.min.css" />
    <script src="js/modernizr.custom.js"></script>
	<link rel="stylesheet" type="text/css" href="stylesheets/normalize.css" />


</head>
<body>
    
    <div class="nav">
        <form method="post" style="float: right">
            
        <?php
                if(isset($_SESSION['camera']))
                    {
                    
                }  else {
                     header("Location: ./index.php");
                }
                if(isset($_POST['loguot'])){
                    echo session_unset();
                }
                ?>
            
            <input type="submit" name="loguot" value="logout" style="margin-top:15%;   ">
        </form>
    </div>
<div class = "bodyDiv">

<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
<!-- My Phone Number & Dial Areas -->
<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->

    <br><br>
<div id="vid-box"></div>
    
    <br><br>
 <div id="stream-info" hidden="true">
        <button id="end" onclick="end()" hidden="true">Done</button>
        <img src="person_dark.png"/>
        <span id="here-now">0</span>       
</div> 

    <br><br>
<form name="streamForm" id="stream" action="#" onsubmit="return errWrap(stream,this);">
    <span class="input input--nao">
                <?php
                if(isset($_SESSION['camera']))
                    {
                    
                    $camname=str_replace(' ', '',$_SESSION['camera']);;
                    echo '<input class="input__field input__field--nao" type="text" name="streamname" placeholder="Enter Stream Name" id="streamname" value="'.$camname.'" disabled  />';
                }  else {
                     header("Location: ./index.php");
                }
                ?>
                
            
            <label class="input__label input__label--nao" for="streamname">
                    <span class="input__label-content input__label-content--nao">        
                    </span>
                </label>
            <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
            </svg>
    </span>

      <button class="cbutton cbutton--effect-radomir" type="submit" value="Stream" name="stream_submit" style="margin-top: 40px; margin-left:-10px">
            <i class="cbutton__icon fa fa-fw fa fa fa-video-camera"></i>
        </button>
</form>
    

 
    

    
<div id="logs" class="ptext" style="background-color:white"></div>


<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
<!-- WebRTC Peer Connections -->
<!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.pubnub.com/pubnub.min.js"></script>
<script src="js/webrtc.js"></script>
<script src="js/rtc-controller.js"></script>

<script type="text/javascript">

var video_out  = document.getElementById("vid-box");
var embed_code = document.getElementById("embed-code");
var embed_demo = document.getElementById("embed-demo");
var here_now   = document.getElementById('here-now');
var stream_info= document.getElementById('stream-info');
var end_stream = document.getElementById('end');

var streamName;

function stream(form) {
	streamName = form.streamname.value || Math.floor(Math.random()*100)+'';
	var phone = window.phone = PHONE({
	    number        : streamName, // listen on username line else random
	    publish_key   : 'pub-c-561a7378-fa06-4c50-a331-5c0056d0163c', // Your Pub Key
	    subscribe_key : 'sub-c-17b7db8a-3915-11e4-9868-02ee2ddab7fe', // Your Sub Key
	    oneway        : true,
	    broadcast     : true,
	});
	//phone.debug(function(m){ console.log(m); })
	var ctrl = window.ctrl = CONTROLLER(phone, get_xirsys_servers);
	ctrl.ready(function(){
		form.streamname.style.background="#55ff5b";
		form.streamname.value = phone.number(); 
//		form.stream_submit.hidden="true"; 
		ctrl.addLocalStream(video_out);
		ctrl.stream();
        stream_info.hidden=false;
        end_stream.hidden =false;
		addLog("Streaming to " + streamName); 
	});
	ctrl.receive(function(session){
	    session.connected(function(session){ addLog(session.number + " has joined."); });
	    session.ended(function(session) { addLog(session.number + " has left."); console.log(session)});
	});
	ctrl.streamPresence(function(m){
		here_now.innerHTML=m.occupancy;
		addLog(m.occupancy + " currently watching.");
	});
	return false;
}

function watch(form){
	var num = form.number.value;
	var phone = window.phone = PHONE({
	    number        : "Viewer" + Math.floor(Math.random()*100), // listen on username line else random
	    publish_key   : 'pub-c-561a7378-fa06-4c50-a331-5c0056d0163c', // Your Pub Key
	    subscribe_key : 'sub-c-17b7db8a-3915-11e4-9868-02ee2ddab7fe', // Your Sub Key
	    oneway        : true
	});
	var ctrl = window.ctrl = CONTROLLER(phone, get_xirsys_servers);
	ctrl.ready(function(){
		ctrl.isStreaming(num, function(isOn){
			if (isOn) ctrl.joinStream(num);
			else alert("User is not streaming!");
		});
		addLog("Joining stream  " + num); 
	});
	ctrl.receive(function(session){
	    session.connected(function(session){ 
            video_out.appendChild(session.video); 
            addLog(session.number + " has joined.");
            stream_info.hidden=false;
        });
	    session.ended(function(session) { addLog(session.number + " has left."); });
	});
	ctrl.streamPresence(function(m){
		here_now.innerHTML=m.occupancy;
		addLog(m.occupancy + " currently watching.");
	});
	return false;
}

function getVideo(number){
	return $('*[data-number="'+number+'"]');
}

function addLog(log){
	$('#logs').append("<p>"+log+"</p>");
}

function end(){
	if (!window.phone) return;
	ctrl.hangup();
    video_out.innerHTML = "";
//	phone.pubnub.unsubscribe(); // unsubscribe all?
}

function genEmbed(w,h){
	if (!streamName) return;
	var url = "embed.html?stream=" + streamName;
	alert(url);
	var embed    = document.createElement('iframe');
	embed.src    = url;
	embed.width  = w;
	embed.height = h;
	embed.setAttribute("frameborder", 0);
	embed_demo.innerHTML = '<a href="embed_demo.html?stream="'+streamName+'"&width="'+w+'"&height="'+h+'">Embed Demo</a>"'
	embed_code.innerHTML = 'Embed Code: ';
	embed_code.appendChild(document.createTextNode(embed.outerHTML));
}

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
// Request fresh TURN servers from XirSys - Need to explain.
// room=default&application=default&domain=kevingleason.me&ident=gleasonk&secret=b9066b5e-1f75-11e5-866a-c400956a1e19
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
function get_xirsys_servers() {
    var servers;
    $.ajax({
        type: 'POST',
        url: 'https://service.xirsys.com/ice',
        data: {
            room: 'default',
            application: 'default',
            domain: 'safin.tk',
            ident: 'safen',
            secret: 'c6818886-714f-11e6-b083-82dee234891e',
            secure: 1,
        },
        success: function(res) {
	        console.log(res);
            res = JSON.parse(res);
            if (!res.e) servers = res.d.iceServers;
        },
        async: false
    });
    return servers;
}

function errWrap(fxn, form){
	try {
		return fxn(form);
	} catch(err) {
		alert("WebRTC is currently only supported by Chrome, Opera, and Firefox");
		return false;
	}
}

</script>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new
		Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','js/analytics.js','ga');

	ga('create', 'UA-46933211-3', 'auto');
	ga('send', 'pageview');

</script>

</div>
</body>
</html>
