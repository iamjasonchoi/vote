loading...

<script>
	localStorage.setItem('URL', {{ $res['url'] }});
	localStorage.setItem('vote_model_id', {{ $res['id'] }});
	localStorage.setItem('vote_name', {{ $res['name'] }});
	window.href.location = 'vote.leekachung.cn';
</script>