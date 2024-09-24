$(document).ready(() => {
 
	$('#index').on('click', () => {
	  $('body').load('index.html')
	})
	
	$('#documentacao').on('click', () => {
	  $.post('documentacao.html', data => {
		$('#pagina').html(data)
	  })
	})
   
	$('#suporte').on('click', () => {
	  $('#pagina').load('suporte.html') 
	})
   

   
	$('#competencia').on('change', (e) => {
   
	  let competencia = $(e.target).val()
      
	  $.ajax({
		
		type: 'GET',
		url: 'app.php',
		data: `competencia=${competencia}`, // x-www-form-urlencoded
		dataType: 'json',
		success: (dados) => { 
		  $('#numeroVendas').html(dados.numeroVendas)
		  $('#totalVendas').html(dados.totalVendas)
		  $('#clientesAtivos').html(dados.clientesAtivos)
		  $('#clientesInativos').html(dados.clientesInativos)
		  $('#totalReclamacoes').html(dados.totalReclamacoes)
		  $('#totalElogios').html(dados.totalElogios)
		  $('#totalSugestoes').html(dados.totalSugestoes)
		  $('#totalDespesas').html(dados.totalDespesas)
		 },
   
		error: (erro) => { console.log(erro) }
   
	  })
   
	})
   
   
  }) 