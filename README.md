# Docs CKEDITOR Builder

<p>O CKEDITOR é um editor HTML WYSIWYG, baseado em bootstrap e ampla compatibilidade com navegadores, incluindo navegadores legados. Esse projeto tem por objetivo explorar essa biblioteca construída em javascript.</p>

<p>Quer saber mais sobre o CKEDITOR? Acesse o site oficial: <a href="https://ckeditor.com" target="_blank">https://ckeditor.com</a>.</p>

### Como utilizar?

<p>Antes de mais nada é necessário chamar o arquivo que contém a classe no seu arquivo principal</p>

	require_once "src/Ckeditor.php";
    
<p>E logo em seguida crie um objeto da classe:</p>
    
    $builder = new Ckeditor();

<p>Os seguintes métodos estarão disponíveis para acesso</p>
	
<ul>

<li>
<strong><code>setPath(string $path)</code></strong>: 
com esse método é possivel alterar o caminho original que aponta para os arquivos js e css da biblioteca do CKEDITOR;	
</li>
    
<li>
<strong><code>setEditable(bool $editable)</code></strong>: 
esse método deve ser chamado logo depois da instância da classe, passando como parâmentro o boleano <code>true</code>. Somente assim, será possivel criar os elementos com editor;	
</li>
    
<li>
<strong><code>setSettings(array $settings)</code></strong>: 
esse método altera as configurações padrões do editor.
<p>
   	
    $builder->setSettings([
    	'enterMode' => 'CKEDITOR.ENTER_BR',
        'language' => '"fr"',
        'uiColor' => '"#9AB8F3"'
    ]);    
</p>
<p>Consulte a <a href="https://ckeditor.com/docs/ckeditor4/latest/guide/dev_configuration.html" target="_blank">documentação clicando aqui.</a></p>
</li>



</ul>













