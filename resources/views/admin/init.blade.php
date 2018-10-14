<script>
	localStorage.setItem('URL', "{{ $res['url'] }}");
	localStorage.setItem('vote_model_id', "{{ $res['id'] }}");
	localStorage.setItem('vote_name', "{{ $res['name'] }}");
	window.location.href = 'file:///Users/aguwangfo/code/vote_html/index.html';
</script>