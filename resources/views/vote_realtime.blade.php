
<head runat="server">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title>实时显示票数</title>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.1.0.rc2/echarts.min.js"></script>

<script>

var myChart1;

var Num = [];

var Candidate = [];

window.onload = function () {
	$.ajax({

        type: "GET",

		url:  "{{ $url }}",

        dataType: "json",

		async:false,
		
		timeout: 5000,
        
        cache:false,

        data: {
        	'vote_model_id': {{ $id }}
        },

        headers: { 
        	'X-CSRF-TOKEN' : '{{ csrf_token() }}' 
        },

        success: function (data) {
        	console.log(data);
        	if (data.status_code == 200) {
        		var arr = data.message;

        		for (var i=0; i < arr.length; i++) {
        			Candidate.push(arr[i].name);
        			Num.push(arr[i].vote_num);
        		}
        	}
        },

    });
 	
 	myChart1 = echarts.init(document.getElementById('vote'));
    
    SetOptions(Num);
 
     //ws();
}

function SetOptions(data)
{
    
    var option = {
	    color: ['#3398DB'],
	    tooltip : {
	        trigger: 'axis',
	        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
	            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
	        }
	    },
	    grid: {
	        left: '3%',
	        right: '4%',
	        bottom: '3%',
	        containLabel: true
	    },
	    xAxis : [
	        {
	            type : 'category',
	            data : Candidate,
	            axisTick: {
	                alignWithLabel: true
	            }
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value'
	        }
	    ],
	    series : [
	        {
	            name: '得票数',
	            type:'bar',
	            barWidth: '60%',
	            data: data
	        }
	    ]
	};

    myChart1.setOption(option);
    window.onresize = myChart1.resize;
}

function ws() {
	var socket;
	if (!window.WebSocket) {
	    window.WebSocket = window.MozWebSocket;
	}
	if (window.WebSocket) {
	    socket = new WebSocket("ws://192.168.64.131:8080/ws");
	    socket.onmessage = function (event) {
	        var data = (event.data + '').split(',');
	        SetOptions(data);
	    };
	    socket.onopen = function (event) {
	        console.info("连接开启");
	    };
	    socket.onclose = function (event) {
	        console.info("连接被关闭");	
	    };
	} else {
	    alert("你的浏览器不支持 WebSocket！");
	};

}

</script>

</head>

<body>
	<form id="form1" runat="server">
	<div style="width:100%;height:10%;text-align: center;">
		<h1>实时票数显示 - 星空技术支持</h1>	
	</div>	
    <div id="vote" style="width:100%;height:90%;">
    
    </div>
    </form>	
</body>